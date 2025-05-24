<?php
namespace Modules\Rapat\Http\Service\Implementation;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Modules\Rapat\Http\Helper\KriteriaPenilaian;

class WhatsappService
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }
    public function sendMessageRapat($agendaRapat, $status)
    {
        try {
            $tempatRapat = '';
            $headerMsg   = '';
            switch ($status) {
                case 'tambahRapat':
                    $headerMsg = "*[Pemberitahuan Rapat]*\n\n";
                    break;
                case 'batalRapat':
                    $headerMsg = "*[Pemberitahuan Pembatalan Rapat]*\n\n";
                    break;
                case 'jadwalUlangRapat':
                    $headerMsg = "*[Pemberitahuan Jadwal Ulang Rapat]*\n\n";
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
            $waktuSelesai    = $agendaRapat->waktu_selesai == null ? "SELESAI" : Carbon::parse($agendaRapat->waktu_selesai)->format('H:i');
            $messageTemplate = $headerMsg .
            "Yth. Bapak/Ibu/Saudara/i,\n\n" .
            "Dengan hormat, kami mengundang Anda untuk hadir dalam rapat yang akan dilaksanakan dengan rincian sebagai berikut:\n\n" .
            "📌 *Agenda Rapat:* \n" . $agendaRapat->agenda_rapat . "\n\n" .
            "🗓️ *Waktu:* \n" . Carbon::parse($agendaRapat->waktu_mulai)->translatedFormat('l, d F Y') . ", Pukul " . Carbon::parse($agendaRapat->waktu_mulai)->format('H:i')
            . " - " . $waktuSelesai . " WIB\n\n" .
            $tempatRapat .
            "👤 *Pimpinan Rapat:* \n" . $agendaRapat->rapatAgendaPimpinan->formatted_name . "\n\n" .
            "✅ *Konfirmasi Kesediaan Hadir:* \n" .
            "🔗 {{link_konfirmasi}}\n\n" .
            "📅 *Tambahkan ke Google Calendar:* \n" .
            "🔗 " . $agendaRapat->calendar_link . "\n\n" .
                "Demikian pemberitahuan ini kami sampaikan. Mohon kesediaannya untuk hadir tepat waktu. Atas perhatian dan partisipasinya, kami ucapkan terima kasih.\n\n" .
                "Hormat kami,\nPoliteknik Negeri Banyuwangi";

            foreach ($agendaRapat->rapatAgendaPeserta as $value) {
                $data = [
                    'username'        => $value->username,
                    'rapat_agenda_id' => $agendaRapat->id,
                ];
                $linkKonfirmasiKesediaanRapat = $this->createKesediaanRapatLink($data);
                $message                      = str_replace('{{link_konfirmasi}}', $linkKonfirmasiKesediaanRapat, $messageTemplate);
                //mengirim pesan
                $response = Http::post(env('WA_URL'), [
                    'session'     => 'default',
                    'chatId'      => '6282264349638@c.us',
                    'text'        => $message,
                    'linkPreview' => false,
                ]);
            }
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());
        }
    }
    public function sendMessagePenugasan($agendaRapat, $tindakLanjut, $status)
    {
        $agenda            = $agendaRapat->agenda_rapat;
        $pegawai           = $tindakLanjut->pegawai->nama;
        $tanggalPenugasan  = Carbon::parse($tindakLanjut->created_at)->translatedFormat('l, d F Y');
        $deskripsiTugas    = $tindakLanjut->deskripsi_tugas;
        $batasPenyelesaian = Carbon::parse($tindakLanjut->batas_waktu)->translatedFormat('l, d F Y');
        try {
            $message = "📢 *Pemberitahuan Penugasan Tindak Lanjut Rapat*\n\n" .
                "Anda telah ditugaskan dalam agenda rapat berikut:\n\n" .
                "👤 *Nama Pegawai:* {$pegawai}" . " \n\n" .
                "📝 *Agenda Rapat:* {$agenda}" . " \n\n" .
                "📅 *Tanggal Penugasan:* {$tanggalPenugasan}" . " \n\n" .
                "🧾 *Deskripsi Tugas:* {$deskripsiTugas}" . " \n\n" .
                "⏳ *Batas Penyelesaian:* {$batasPenyelesaian}" . " \n\n" .
                "Mohon untuk menyelesaikan tugas sesuai dengan batas waktu yang telah ditentukan.\n\n" .
                "Terima kasih.\n" .
                "" .
                "Politeknik Negeri Banyuwangi";

            Http::post(env('WA_URL'), [
                'session'     => 'default',
                'chatId'      => '6282264349638@c.us',
                'text'        => $message,
                'linkPreview' => false,
            ]);
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());
        }

    }
    public function sendMessagePenilaian($agendaRapat, $tindakLanjut, $status)
    {

        try {
            $namaAgenda   = $agendaRapat->agenda_rapat;
            $namaPimpinan = $agendaRapat->rapatAgendaPimpinan->nama;
            $nilai        = KriteriaPenilaian::from($tindakLanjut->penilaian)->label();
            $komentar     = $tindakLanjut->komentar;
            $message      = "✅ *Penilaian Tugas Rapat*\n\n" .
                "Tugas yang Anda kumpulkan pada agenda rapat \n\n" . " :*{$namaAgenda}* " . "\n\n" . "telah dinilai oleh pimpinan rapat.\n\n" .
                "👤 *Pimpinan:* {$namaPimpinan}" . "\n\n" .
                "📝 *Kriteria Penilaian:* {$nilai}" . "\n\n" .
                "💬 *Komentar:* {$komentar}" . "\n\n" .
                "Terima kasih atas kontribusi Anda." . "\n\n" .
                "_Sistem Manajemen Rapat_";

            Http::post(env('WA_URL'), [
                'session'     => 'default',
                'chatId'      => '6282264349638@c.us',
                'text'        => $message,
                'linkPreview' => false,
            ]);

        } catch (\Throwable $th) {
            logger()->error($th->getMessage());
        }
    }
    public function sendMessageKepanitiaan($kepanitiaan, $type)
    {
        try {
            $header          = $type == 'create' ? "👤 Pemberitahuan Penambahan Kepanitiaan\n\n" : "👤 Pemberitahuan Perubahan Kepanitiaan\n\n";
            $messageTemplate = "{{header}}, {{nama_pegawai}}\n\n" .
                " Kami ingin menginformasikan bahwa Anda telah **ditambahkan sebagai anggota kepanitiaan baru** dengan rincian sebagai berikut:\n\n" .
                "📝 Nama Kepanitiaan: {{nama_kepanitiaan}}\n" .
                "📅 Tanggal Mulai: {{tgl_mulai}}\n" .
                "📅 Tanggal Berakhir: {{tgl_berakhir}}\n" .
                "👤 Pimpinan Kepanitiaan: {{nama_pimpinan}}\n\n" .
                "Kami mengharapkan partisipasi aktif Anda dalam menjalankan tugas kepanitiaan ini sesuai dengan peran dan tanggung jawab yang diberikan.\n" .
                "Silakan periksa klik link berikut untuk melihat detail lengkapnya.\n\n" .
                "Link: {{link}}\n\n" .
                "Terima kasih atas perhatiannya\n";
            foreach ($kepanitiaan->pegawai as $pegawai) {
                $data = [
                    '{{header}}'           => $header,
                    '{{nama_pegawai}}'     => $pegawai->formatted_name,
                    '{{nama_kepanitiaan}}' => $kepanitiaan->nama_kepanitiaan,
                    '{{tgl_mulai}}'        => \Carbon\Carbon::parse($kepanitiaan->tanggal_mulai)->translatedFormat('d F Y'),
                    '{{tgl_berakhir}}'     => \Carbon\Carbon::parse($kepanitiaan->tanggal_berakhir)->translatedFormat('d F Y'),
                    '{{nama_pimpinan}}'    => $kepanitiaan->ketua->formatted_name,
                    '{{link}}'             => env('APP_URL') . '/rapat/panitia/' . $kepanitiaan->slug . '/detail',
                ];
                $message  = str_replace(array_keys($data), array_values($data), $messageTemplate);
                $response = Http::post(env('WA_URL'), [
                    'session'     => 'default',
                    'chatId'      => '6282264349638@c.us',
                    'text'        => $message,
                    'linkPreview' => false,
                ]);
            }
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());

        }

    }
    public function createKesediaanRapatLink($data)
    {
        $token = Crypt::encrypt($data);
        return env('APP_URL') . '/rapat/agenda-rapat/konfirmasi/' . $token;
    }
}
