<?php
namespace Modules\Rapat\Http\Service\Implementation;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Modules\Rapat\Http\Service\MeetingServiceInterface;

class ZoomServiceImpl implements MeetingServiceInterface
{
    public function authentication()
    {
        try {
            $encoded  = base64_encode(env('ZOOM_CLIENT_ID') . ":" . env('ZOOM_CLIENT_SECRET'));
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $encoded,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ])->post('https://zoom.us/oauth/token?grant_type=account_credentials&account_id=RuLWfh_qQHuK796Hj4hPOw');
            Session::put('zoom_token', $response->collect()['access_token']);
            Session::save();
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
    public function createMeeting($data)
    {
        if (! Session::exists('zoom_token')) {
            $this->authentication();
        }
        try {
            $waktuMulai   = Carbon::parse($data->waktu_mulai)->setTimezone('UTC')->toIso8601String();
            $waktuSelesai = Carbon::parse($data->waktu_selesai)->setTimezone('UTC')->toIso8601String();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('zoom_token'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.zoom.us/v2/users/me/meetings', [
                "agenda"            => $data->agenda_rapat,
                "duration"          => (int) Carbon::parse($waktuSelesai)->diffInSeconds(Carbon::parse($waktuMulai)) / 60,
                "password"          => "123456",
                "alternative_hosts" => $data->rapatAgendaPimpinan->email,
                "settings"          => [
                    "approval_type"      => 2,
                    "audio"              => "telephony",
                    "contact_email"      => $data->rapatAgendaPimpinan->email,
                    "contact_name"       => $data->rapatAgendaPimpinan->name,
                    "email_notification" => true,
                    "host_video"         => true,
                    "participant_video"  => true,
                    "join_before_host"   => true,
                    "waiting_room"       => false,
                ],
                "start_time"        => $waktuMulai,
                "timezone"          => "Asia/Jakarta",
                "topic"             => $data->agenda_rapat,
                "type"              => 2,
            ]);
            $data->update([
                'zoom_link' => $response->collect()['join_url'],
            ]);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}
