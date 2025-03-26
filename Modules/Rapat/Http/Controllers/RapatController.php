<?php
namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Service\Implementation\RapatService;

class RapatController extends Controller
{
    protected $rapatService;
    public function __construct(RapatService $rapatService)
    {
        $this->rapatService = $rapatService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $rapat = RapatAgenda::orderBy('created_at', 'desc')->get();
        // $userId = Auth::user()->id;
        // $rapat  = RapatAgenda::userIsPeserta($userId)->get();
        return view('rapat::rapat.index', [
            'rapats' => $rapat,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $users       = User::with(['rapatAgendaPeserta', 'kepanitiaans'])->get();
        $kepanitiaan = Kepanitiaan::with('users')->get();
        return view('rapat::rapat.create', [
            'users'        => $users,
            'kepanitiaans' => $kepanitiaan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {}

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta', 'rapatLampiran']);
        return view('rapat::rapat.detail-rapat', [
            'rapat' => $rapatAgenda,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
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

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
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
