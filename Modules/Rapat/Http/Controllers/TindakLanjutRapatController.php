<?php

namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Entities\RapatTindakLanjut;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Requests\CreateTugasPesertaRapatRequest;
use Modules\Rapat\Http\Service\Implementation\TindakLanjutRapatService;

class TindakLanjutRapatController extends Controller
{
    private $tindakLanjutRapatService;
    public function __construct(TindakLanjutRapatService $tindakLanjutRapatService)
    {
        $this->tindakLanjutRapatService = $tindakLanjutRapatService;
    }
    public function index()
    {
        $agendaRapat = RapatAgenda::with('rapatTindakLanjut')->showTindakLanjut(Auth::user()->id)->get();
        $data = [];
        $statusTindakLanjut = [
            'SELESAI' => 'success',
            'BELUM SELESAI' => 'danger',
        ];
        foreach ($agendaRapat as $key => $rapat) {
            $data[] = [
                '<div class="text-center">' . ($key + 1) . '</div>',
                $rapat->agenda_rapat,
                '<div class="text-center">
        <span class="badge badge-' . $statusTindakLanjut[$rapat->status_tindak_lanjut] . '">
            ' . $rapat->status_tindak_lanjut . '
        </span>
        </div>',
                '<div class="text-center">
            <a href="' . url('rapat/tindak-lanjut-rapat/' . $rapat->slug . '/detail') . '" class="btn btn-secondary">
                Detail
            </a>
        </div>',
            ];
        }
        $heads = [
            ['label' => 'No', 'width' => 5, 'class' => 'text-center'],
            ['label' => 'Agenda Rapat', 'width' => 40],
            ['label' => 'Status', 'width' => 10, 'class' => 'text-center'],
            ['label' => 'Aksi', 'width' => 10, 'class' => 'text-center'],
        ];
        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [
                ['className' => 'text-center'],
                null,
                ['className' => 'text-center', 'orderable' => false],
                ['className' => 'text-center', 'orderable' => false],
            ],
        ];
        return view('rapat::rapat.tindak-lanjut.index', [
            'agendaRapat' => $agendaRapat,
            'config'         => $config,
            'heads'          => $heads
        ]);
    }
    public function show(RapatAgenda $rapatAgenda)
    {
        $tindakLanjut = $rapatAgenda->rapatTindakLanjut()->userHaveTugas(Auth::user(), $rapatAgenda)->with('rapatTindakLanjutFile', 'rapatAgenda')->get();
        return view('rapat::rapat.tindak-lanjut.lihat-tindak-lanjut', [
            'rapat'         => $rapatAgenda,
            'tindakLanjuts' => $tindakLanjut
        ]);
    }
    public function isiPenugasan(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta']);
        $data = [];
        $btnPenugasan = '';
        foreach ($rapatAgenda->rapatAgendaPeserta as $key => $peserta) {
            if ($peserta->pivot->is_penugasan == false) {
                $btnPenugasan = '<a href="' . url('/rapat/agenda-rapat/' . $rapatAgenda->slug . '/tugaskan/' . $peserta->id) . '" class="btn btn-primary">Tugaskan</a>';
            } else {
                $btnPenugasan = ' <button class="btn btn-danger">Sudah Ditugaskan</button>';
            }
            $data[] = [
                $key + 1,
                $peserta->name,
                $btnPenugasan
            ];
        }
        return view('rapat::rapat.tindak-lanjut.input-penugasan', [
            'rapat' => $rapatAgenda,
            'data'  => $data
        ]);
    }
    public function tugaskanPesertaRapat(RapatAgenda $rapatAgenda, User $user)
    {
        $this->isUserArePesertaRapat($rapatAgenda, $user);
        return view('rapat::rapat.tindak-lanjut.tugaskan', [
            'rapat'   => $rapatAgenda,
            'peserta' => $user,
        ]);
    }
    public function createTugasPesertaRapat(RapatAgenda $rapatAgenda, User $user, CreateTugasPesertaRapatRequest $request)
    {
        $this->isUserArePesertaRapat($rapatAgenda, $user);
        try {
            $this->tindakLanjutRapatService->createTugasPesertaRapat($rapatAgenda, $user, $request);
            FlashMessage::success('Tugas Berhasil Ditambahkan');
            return redirect()->to('/rapat/agenda-rapat/' . $rapatAgenda->slug . '/tugas');
        } catch (\Throwable $e) {
            FlashMessage::error("Gagal Menambahkan Tugas");
            return redirect()->to('/rapat/agenda-rapat/' . $rapatAgenda->slug . '/tugas');
        }
    }

    public function uploadTugas(RapatTindakLanjut $rapatTindakLanjut)
    {
        return view('rapat::rapat.tindak-lanjut.upload-tugas', [
            'rapatTindakLanjut' => $rapatTindakLanjut
        ]);
    }
    function isUserArePesertaRapat(RapatAgenda $rapatAgenda, User $user)
    {
        if (!$rapatAgenda->rapatAgendaPeserta->contains($user)) {
            abort(404);
        }
        return;
    }
}
