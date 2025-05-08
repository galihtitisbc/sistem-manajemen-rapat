<?php
namespace Modules\Rapat\Http\Service\Implementation;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WhatsappService
{
    public function sendMessageRapat($agendaRapat, $status)
    {
        try {
            Carbon::setLocale('id');
            $tempatRapat = '';
            $headerMsg   = '';
            switch ($status) {
                case 'tambahRapat':
                    $headerMsg = "*[Pemberitahuan Rapat]*\n\n";
                    break;
                case 'batalRapat':
                    $headerMsg = "*[Pemberitahuan Batal Rapat]*\n\n";
                    break;
                case 'updateRapat':
                    $headerMsg = "*[Pemberitahuan Perubahan Rapat]*\n\n";
                    break;
                default:
                    break;
            }
            if ($agendaRapat->tempat === 'zoom') {
                $tempatRapat =
                "📍 *Tempat (Online):* \nZoom Meeting\n" .
                "🔗 " . $agendaRapat->zoom_link . "\n";
            } else {
                $tempatRapat =
                "📍 *Tempat:* \n" . $agendaRapat->tempat . "\n\n";
            }
            $waktuSelesai = $agendaRapat->waktu_selesai == null ? "SELESAI" : Carbon::parse($agendaRapat->waktu_selesai)->format('H:i');
            $message      = $headerMsg .
            "Yth. Bapak/Ibu/Saudara/i,\n\n" .
            "Dengan hormat, kami mengundang Anda untuk hadir dalam rapat yang akan dilaksanakan dengan rincian sebagai berikut:\n\n" .
            "📌 *Agenda Rapat:* \n" . $agendaRapat->agenda_rapat . "\n\n" .
            "🗓️ *Waktu:* \n" . Carbon::parse($agendaRapat->waktu_mulai)->translatedFormat('l, d F Y') . ", Pukul " . Carbon::parse($agendaRapat->waktu_mulai)->format('H:i')
            . " - " . $waktuSelesai . " WIB\n\n" .
            $tempatRapat .
            "👤 *Pimpinan Rapat:* \n" . $agendaRapat->rapatAgendaPimpinan->nama . "\n\n" .
            "✅ *Konfirmasi Kesediaan Hadir:* \n" .
            "🔗 " . $agendaRapat->calendar_link . "\n\n" .
            "📅 *Tambahkan ke Google Calendar:* \n" .
            "🔗 " . $agendaRapat->calendar_link . "\n\n" .
                "Demikian pemberitahuan ini kami sampaikan. Mohon kesediaannya untuk hadir tepat waktu. Atas perhatian dan partisipasinya, kami ucapkan terima kasih.\n\n" .
                "Hormat kami,\nPoliteknik Negeri Banyuwangi";

            //mengirim pesan
            $response = Http::post(env('WA_URL'), [
                'session' => 'default',
                'chatId'  => '6282264349638@c.us',
                'text'    => $message,
            ]);
        } catch (\Throwable $th) {

        }
    }
    public function sendMessagePenugasan($agendaRapat)
    {}

}
