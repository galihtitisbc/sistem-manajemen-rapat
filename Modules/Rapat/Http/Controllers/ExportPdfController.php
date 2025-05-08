<?php
namespace Modules\Rapat\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Routing\Controller;
use Modules\Rapat\Entities\RapatAgenda;

class ExportPdfController extends Controller
{
    public function generateNotulenRapat(RapatAgenda $rapatAgenda)
    {
        $rapatAgenda->load(['rapatAgendaPimpinan', 'rapatAgendaNotulis', 'rapatAgendaPeserta', 'rapatTindakLanjut.pegawai', 'rapatLampiran', 'rapatNotulen.notulenFiles', 'rapatDokumentasi']);

        //image harus diubah ke base64 dulu baru bisa ditampilkan di dompdf, entah mengapa
        $logoPath   = public_path('assets/img/pdf/Logo_Politeknik_Negeri_Banyuwangi.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $type       = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data       = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $html = view('rapat::rapat.pdf.notulen', [
            'rapat' => $rapatAgenda->toArray(),
            'logo'  => $logoBase64,
        ])->render();

        $pdf = new Dompdf();
        $pdf->loadHtml($html);
        $pdf->render();

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan.pdf"');

        // return view('rapat::rapat.pdf.notulen', ['rapat' => $rapatAgenda->toArray(), 'logo' => $logoBase64]);

    }
}
