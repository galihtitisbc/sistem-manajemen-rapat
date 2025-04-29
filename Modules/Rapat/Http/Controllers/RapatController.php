<?php

namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $users       = User::with(['rapatAgendaPeserta', 'kepanitiaans'])->paginate(10);
        $kepanitiaan = Kepanitiaan::with('users')->where('status', 'AKTIF')->get();
        return view('rapat::rapat.create', [
            'users'        => $users,
            'kepanitiaans' => $kepanitiaan,
        ]);
    }
    public function ajaxPesertaRapat(Request $request)
    {
        $query = User::with(['rapatAgendaPeserta', 'kepanitiaans']);
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $total = User::count();
        $filtered = $query->count();

        $data = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }
    public function ajaxSelectedPesertaRapat(Request $request)
    {
        $idPeserta = explode(',', $request->id);
        $query = User::whereIn('id', $idPeserta);
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $total = User::count();
        $filtered = $query->count();

        $data = $query
            ->offset($request->input('start'))
            ->limit($request->input('length'))
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
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
