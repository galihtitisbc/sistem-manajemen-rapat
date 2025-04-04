<?php
namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Routing\Controller;
use Modules\Rapat\Entities\RapatAgenda;

class TindakLanjutRapatController extends Controller
{
    public function index()
    {
        return view('rapat::index');
    }
    public function isiPenugasan(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta']);
        return view('rapat::rapat.tindak-lanjut.input-penugasan', [
            'rapat' => $rapatAgenda,
        ]);
    }
    public function tugaskanPesertaRapat(RapatAgenda $rapatAgenda, User $user)
    {
        return view('rapat::rapat.tindak-lanjut.tugaskan', [
            'rapat'   => $rapatAgenda,
            'peserta' => $user,
        ]);
    }
}
