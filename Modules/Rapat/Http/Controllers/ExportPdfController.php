<?php
namespace Modules\Rapat\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Rapat\Entities\RapatAgenda;

class ExportPdfController extends Controller
{
    public function generateNotulenRapat(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta', 'rapatTindakLanjut', 'rapatLampiran', 'rapatNotulen.notulenFiles']);
        // return $rapatAgenda->toArray();
        // $pdf = Pdf::loadView('rapat::rapat.pdf.notulen', ['test']);
        // return $pdf->download('invoice.pdf');
        return view('rapat::rapat.pdf.notulen', ['rapat' => $rapatAgenda->toArray()]);
    }
}
