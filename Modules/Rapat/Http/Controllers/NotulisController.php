<?php

namespace Modules\Rapat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Requests\UploadNotulenRequest;
use Modules\Rapat\Http\Service\Implementation\NotulisService;

class NotulisController extends Controller
{
    private $notulisService;
    public function __construct(NotulisService $notulisService)
    {
        $this->notulisService = $notulisService;
    }
    public function formUnggahNotulen(RapatAgenda $rapatAgenda)
    {
        $rapat = $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta']);

        return view('rapat::rapat.notulis.unggah-notulen', [
            'agendaRapat' => $rapat
        ]);
    }
    public function storeNotulen(RapatAgenda $rapatAgenda, UploadNotulenRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        try {
            $this->notulisService->storeNotulen($rapatAgenda, $validated);
            FlashMessage::success('Status rapat berhasil diubah');
            return redirect()->to('/rapat/agenda-rapat');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
