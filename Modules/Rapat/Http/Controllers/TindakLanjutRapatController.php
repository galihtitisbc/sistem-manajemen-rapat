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
        return view('rapat::rapat.tindak-lanjut.index', [
            'agendaRapat' => $agendaRapat
        ]);
    }
    public function show(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatTindakLanjut', 'rapatTindakLanjut.user']);
        return view('rapat::rapat.tindak-lanjut.lihat-tindak-lanjut', [
            'rapat' => $rapatAgenda
        ]);
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

    function isUserArePesertaRapat(RapatAgenda $rapatAgenda, User $user)
    {
        if (!$rapatAgenda->rapatAgendaPeserta->contains($user)) {
            abort(404);
        }
        return;
    }
}
