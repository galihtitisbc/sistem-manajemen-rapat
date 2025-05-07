<?php
namespace Modules\Rapat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Helper\StatusAgendaRapat;

class RiwayatRapatController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $rapats = RapatAgenda::pegawaiIsPesertaOrCreator(Auth::user()->pegawai->username)->orderBy('created_at', 'desc')->where('status', StatusAgendaRapat::COMPLETED->value)->paginate(10);
        return view('rapat::rapat.riwayat.index', [
            'rapats' => $rapats,
        ]);
    }

}
