<?php
namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Entities\Pegawai;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Service\Implementation\RapatService;
use Modules\Rapat\Http\Traits\AgendaRapatDatatables;

class RapatController extends Controller
{
    use AgendaRapatDatatables;
    protected $rapatService;
    public function __construct(RapatService $rapatService)
    {
        $this->rapatService = $rapatService;
    }

    public function index()
    {
        // mengambil data agenda rapat dari trait AgendaRapatDatatables
        return view('rapat::rapat.index', [
            'config' => $this->getAgendaRapatDatatables()['config'],
            'heads'  => $this->getAgendaRapatDatatables()['heads'],
        ]);
    }
    public function create()
    {
        $kepanitiaan = Kepanitiaan::where('status', 'AKTIF')->get();
        return view('rapat::rapat.create', [
            'kepanitiaans' => $kepanitiaan,
        ]);
    }
    public function ajaxPesertaRapat(Request $request)
    {
        $query = Pegawai::with(['user', 'rapatAgendaPeserta', 'kepanitiaans']);
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }
        $total    = Pegawai::count();
        $filtered = $query->count();

        $data = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }
    public function ajaxSelectedPesertaRapat(Request $request)
    {
        $usernamePeserta = explode(',', $request->username);
        $query           = Pegawai::whereIn('username', $usernamePeserta)->with(['user', 'rapatAgendaPeserta', 'kepanitiaans']);
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('user.email', 'like', "%{$search}%");
            });
        }
        $total    = Pegawai::count();
        $filtered = $query->count();

        $data = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pimpinan_username' => 'required|exists:pegawais,username',
            'kepanitiaan_id'    => 'nullable|exists:kepanitiaans,id',
            'peserta_rapat'     => 'required|array',
            'peserta_rapat.*'   => 'exists:pegawais,username',
            'notulis_username'  => 'required|exists:pegawais,username',
            'nomor_surat'       => 'required|string|max:255',
            'waktu_mulai'       => 'required|date_format:Y-m-d H:i:s',
            'waktu_selesai'     => 'nullable',
            'agenda_rapat'      => 'required|string',
            'tempat'            => 'required|string|max:255',
            'lampiran.*'        => 'nullable|file|mimes:jpg,jpeg,png,doc,docx,xls,xlsx,pdf,txt|max:2048',
        ], [
            'pimpinan_username.required' => 'Pimpinan rapat harus dipilih.',
            'pimpinan_username.exists'   => 'Pimpinan rapat tidak valid.',
            'kepanitiaan_id.exists'      => 'Kepanitiaan yang dipilih tidak valid.',
            'peserta_rapat.required'     => 'Peserta rapat harus diisi.',
            'peserta_rapat.array'        => 'Format peserta rapat tidak sesuai.',
            'peserta_rapat.*.exists'     => 'Peserta rapat tidak valid.',
            'notulis_username.required'  => 'Notulis rapat harus dipilih.',
            'notulis_username.exists'    => 'Notulis rapat tidak valid.',
            'nomor_surat.required'       => 'Nomor surat harus diisi.',
            'nomor_surat.string'         => 'Nomor surat harus berupa teks.',
            'nomor_surat.max'            => 'Nomor surat maksimal 255 karakter.',
            'waktu_mulai.required'       => 'Waktu mulai rapat harus diisi.',
            'waktu_mulai.date_format'    => 'Format waktu mulai tidak sesuai (Y-m-d H:i:s).',
            'waktu_selesai.required'     => 'Waktu selesai rapat harus diisi.',
            'waktu_selesai.date_format'  => 'Format waktu selesai tidak sesuai (Y-m-d H:i:s).',
            'waktu_selesai.after'        => 'Waktu selesai harus setelah waktu mulai.',
            'agenda_rapat.required'      => 'Agenda rapat harus diisi.',
            'agenda_rapat.string'        => 'Agenda rapat harus berupa teks.',
            'tempat.required'            => 'Tempat rapat harus diisi.',
            'tempat.string'              => 'Tempat rapat harus berupa teks.',
            'tempat.max'                 => 'Tempat rapat maksimal 255 karakter.',
            'lampiran.*.file'            => 'Lampiran harus berupa file.',
            'lampiran.*.mimes'           => 'Lampiran harus berupa file dengan format: jpg, jpeg, png, doc, docx, xls, xlsx, pdf, atau txt.',
            'lampiran.*.max'             => 'Ukuran lampiran maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $validated = $validator->validated();
            $this->rapatService->store($validated);
            return response()->json([
                'success' => true,
                'message' => 'Rapat berhasil ditambahkan.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'fail'    => true,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function show(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta', 'rapatLampiran']);
        return view('rapat::rapat.detail-rapat', [
            'rapat' => $rapatAgenda,
        ]);
    }
    public function downloadLampiran($file)
    {
        return Storage::download('/rapat/' . $file);
    }
    public function edit(RapatAgenda $rapatAgenda)
    {
        $users       = User::with(['rapatAgendaPeserta', 'kepanitiaans'])->get();
        $kepanitiaan = Kepanitiaan::with('users')->get();
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta', 'rapatLampiran']);
        return view('rapat::rapat.edit-rapat', [
            'rapat'        => $rapatAgenda,
            'users'        => $users,
            'kepanitiaans' => $kepanitiaan,
        ]);
    }
    public function ubahStatusRapat(RapatAgenda $rapatAgenda)
    {
        try {
            $this->rapatService->ubahStatusAgendaRapat($rapatAgenda);
            FlashMessage::success('Status rapat berhasil diubah');
            return redirect()->to('/rapat/agenda-rapat');
        } catch (\Throwable $th) {
            FlashMessage::error($th->getMessage());
            return redirect()->to('/rapat/agenda-rapat');
        }
    }
}
