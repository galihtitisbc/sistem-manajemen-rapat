<?php
namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Rapat\Entities\Pegawai;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Entities\RapatTindakLanjut;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Helper\StatusTindakLanjut;
use Modules\Rapat\Http\Requests\CreateTugasPesertaRapatRequest;
use Modules\Rapat\Http\Requests\UploadTugasTindakLanjutRapatRequest;
use Modules\Rapat\Http\Service\Implementation\TindakLanjutRapatService;
use Modules\Rapat\Rules\EnumKriteriaPenilaianRule;

class TindakLanjutRapatController extends Controller
{
    private $tindakLanjutRapatService;
    public function __construct(TindakLanjutRapatService $tindakLanjutRapatService)
    {
        $this->tindakLanjutRapatService = $tindakLanjutRapatService;
    }
    public function index()
    {
        // $agendaRapat = RapatAgenda::with('rapatTindakLanjut')->showTindakLanjut(Auth::user()->id)->get();
        $tindakLanjutRapat  = RapatTindakLanjut::listAgendaRapatHaveTugas(Auth::user()->pegawai->username)->with('rapatAgenda')->orderBy('created_at', 'asc')->get();
        $data               = [];
        $statusTindakLanjut = [
            'SELESAI'       => 'success',
            'BELUM_SELESAI' => 'danger',
        ];
        // $status         = '';
        $btnDetail  = '';
        $no         = 0;
        $persentase = 0;
        foreach ($tindakLanjutRapat as $key => $tindakLanjut) {

            // $status = '<div class="text-center">
            //      <span class="badge badge-' . $statusTindakLanjut[$tindakLanjut->rapatAgenda->status_tindak_lanjut] . '">
            //          ' . $tindakLanjut->rapatAgenda->status_tindak_lanjut . '
            //      </span></div>';
            if ($tindakLanjut->rapatAgenda->pimpinan_username == Auth::user()->pegawai->username || $tindakLanjut->rapatAgenda->notulis_username == Auth::user()->pegawai->username) {
                $persentase = $tindakLanjut->rapatAgenda->status_persentase_penyelesaian . '%';
            } else {
                $persentase = '<div class="text-center">
     <span class="badge badge-' . $statusTindakLanjut[$tindakLanjut->status] . '">
         ' . StatusTindakLanjut::from($tindakLanjut->status)->label() . '
     </span></div>';

            }
            $btnDetail = '<div class="text-center"> <a href="' . url('rapat/tindak-lanjut-rapat/' . $tindakLanjut->rapatAgenda->slug . '/detail') . '" class="btn btn-secondary">
                        Detail</a></div>';
            $data[] = [
                '<div class="text-center">' . ($no + 1) . '</div>',
                $tindakLanjut->rapatAgenda->agenda_rapat,
                // $status,
                $persentase,
                $btnDetail,
            ];
            $no++;
        }
        $heads = [
            ['label' => 'No', 'width' => 5, 'class' => 'text-center'],
            ['label' => 'Agenda Rapat', 'width' => 40],
            ['label' => 'Penyelesaian', 'width' => 10, 'class' => 'text-center'],
            ['label' => 'Aksi', 'width' => 10, 'class' => 'text-center'],
        ];
        $config = [
            'data'    => $data,
            'columns' => [
                ['className' => 'text-center'],
                null,
                ['className' => 'text-center'],
                ['className' => 'text-center', 'orderable' => false],
            ],
        ];
        return view('rapat::rapat.tindak-lanjut.index', [
            'agendaRapat' => $tindakLanjutRapat,
            'config'      => $config,
            'heads'       => $heads,
        ]);
    }
    public function show(RapatAgenda $rapatAgenda)
    {
        $tindakLanjut = $rapatAgenda->rapatTindakLanjut()->pegawaiHaveTugas(Auth::user()->pegawai, $rapatAgenda)->with(['rapatTindakLanjutFile', 'rapatAgenda', 'pegawai'])->get();
        return view('rapat::rapat.tindak-lanjut.lihat-tindak-lanjut', [
            'rapat'         => $rapatAgenda,
            'tindakLanjuts' => $tindakLanjut,
        ]);
    }
    public function isiPenugasan(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta']);
        $data         = [];
        $btnPenugasan = '';
        foreach ($rapatAgenda->rapatAgendaPeserta as $key => $peserta) {
            if ($peserta->username == $rapatAgenda->notulis_username) {
                continue;
            }
            if ($peserta->username == $rapatAgenda->pimpinan_username) {
                continue;
            }
            if ($peserta->pivot->is_penugasan == false) {
                $btnPenugasan = '<a href="' . url('/rapat/agenda-rapat/' . $rapatAgenda->slug . '/tugaskan/' . $peserta->username) . '" class="btn btn-primary">Tugaskan</a>';
            } else {
                $btnPenugasan = ' <button class="btn btn-danger">Sudah Ditugaskan</button>';
            }
            $data[] = [
                $key + 1,
                $peserta->nama,
                $btnPenugasan,
            ];
        }
        return view('rapat::rapat.tindak-lanjut.input-penugasan', [
            'rapat' => $rapatAgenda,
            'data'  => $data,
        ]);
    }
    public function tugaskanPesertaRapat(RapatAgenda $rapatAgenda, Pegawai $pegawai)
    {
        $this->isUserArePesertaRapat($rapatAgenda, $pegawai);
        return view('rapat::rapat.tindak-lanjut.tugaskan', [
            'rapat'   => $rapatAgenda,
            'peserta' => $pegawai,
        ]);
    }
    public function createTugasPesertaRapat(RapatAgenda $rapatAgenda, Pegawai $pegawai, CreateTugasPesertaRapatRequest $request)
    {
        $this->isUserArePesertaRapat($rapatAgenda, $pegawai);
        $validated = $request->validated();
        try {
            $this->tindakLanjutRapatService->createTugasPesertaRapat($rapatAgenda, $pegawai, $validated);
            FlashMessage::success('Tugas Berhasil Ditambahkan');
            return redirect()->to('/rapat/agenda-rapat/' . $rapatAgenda->slug . '/tugas');
        } catch (\Throwable $e) {
            dd($e->getMessage());
            FlashMessage::error("Gagal Menambahkan Tugas");
            return redirect()->to('/rapat/agenda-rapat/' . $rapatAgenda->slug . '/tugas');
        }
    }

    public function showUploadTugas(RapatTindakLanjut $rapatTindakLanjut)
    {
        return view('rapat::rapat.tindak-lanjut.upload-tugas', [
            'rapatTindakLanjut' => $rapatTindakLanjut,
        ]);
    }
    public function uploadTugas(RapatTindakLanjut $rapatTindakLanjut, UploadTugasTindakLanjutRapatRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->tindakLanjutRapatService->uploadTugas($rapatTindakLanjut, $validated);
            FlashMessage::success('Tugas Berhasil Di Unggah');
            return redirect()->to('/rapat/tindak-lanjut-rapat/' . $rapatTindakLanjut->rapatAgenda->slug . '/detail');
        } catch (\Throwable $e) {
            FlashMessage::error("Gagal Upload Tugas");
            return redirect()->to('/rapat/tindak-lanjut-rapat/' . $rapatTindakLanjut->rapatAgenda->slug . '/detail');
        }
    }
    public function showEditTugas(RapatTindakLanjut $rapatTindakLanjut)
    {
        return view('rapat::rapat.tindak-lanjut.ubah-tugas', [
            'rapatTindakLanjut' => $rapatTindakLanjut,
        ]);
    }
    public function editTugas(RapatTindakLanjut $rapatTindakLanjut, UploadTugasTindakLanjutRapatRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->tindakLanjutRapatService->editTugas($rapatTindakLanjut, $validated);
            FlashMessage::success('Tugas Berhasil Di Edit');
            return redirect()->to('/rapat/tindak-lanjut-rapat/' . $rapatTindakLanjut->rapatAgenda->slug . '/detail');
        } catch (\Throwable $e) {
            FlashMessage::error("Gagal Edit Tugas");
            return redirect()->to('/rapat/tindak-lanjut-rapat/' . $rapatTindakLanjut->rapatAgenda->slug . '/detail');
        }
    }
    public function detailTugas(RapatTindakLanjut $rapatTindakLanjut)
    {
        $rapatTindakLanjut->load(['rapatTindakLanjutFile', 'rapatAgenda']);
        return view('rapat::rapat.tindak-lanjut.detail-tugas', [
            'tindakLanjut' => $rapatTindakLanjut,
        ]);
    }
    public function simpanTugas(RapatTindakLanjut $rapatTindakLanjut, Request $request)
    {
        $validated = $request->validate([
            'kriteria_penilaian' => ['required', new EnumKriteriaPenilaianRule],
            'komentar_penugasan' => 'nullable',
        ]);
        try {
            $this->tindakLanjutRapatService->simpanTugas($validated, $rapatTindakLanjut);
            FlashMessage::success('Penilaian Berhasil Di Simpan');
            return redirect()->to('/rapat/tindak-lanjut-rapat/' . $rapatTindakLanjut->rapatAgenda->slug . '/detail');
        } catch (\Throwable $e) {
            FlashMessage::error("Penilaian Gagal Di Simpan");
            return redirect()->to('/rapat/tindak-lanjut-rapat/' . $rapatTindakLanjut->rapatAgenda->slug . '/detail');
        }
    }

    // untuk cek apakah user adalah peserta
    public function isUserArePesertaRapat(RapatAgenda $rapatAgenda, Pegawai $user)
    {
        if (! $rapatAgenda->rapatAgendaPeserta->contains($user)) {
            abort(404);
        }
        return;
    }
}
