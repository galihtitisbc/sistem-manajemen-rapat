<?php

namespace Modules\Rapat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Requests\UploadNotulenRequest;

class NotulisController extends Controller
{
    public function formUnggahNotulen(RapatAgenda $rapatAgenda)
    {
        $rapat = $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta']);

        return view('rapat::rapat.notulis.unggah-notulen', [
            'agendaRapat' => $rapat
        ]);
    }
    public function storeNotulen(UploadNotulenRequest $request, RapatAgenda $rapatAgenda)
    {
        $validated = $request->validated();
        dd($validated);
    }
}
