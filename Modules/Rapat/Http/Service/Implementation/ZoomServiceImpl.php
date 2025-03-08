<?php
namespace Modules\Rapat\Http\Service\Implementation;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Modules\Rapat\Http\Service\ZoomServiceInterface;
use Session;

class ZoomServiceImpl implements ZoomServiceInterface
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
            $date = Carbon::now()->addDays(2)->setTimezone('UTC')->toIso8601String();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . Session::get('zoom_token'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.zoom.us/v2/users/me/meetings', [
                "agenda"            => "My Meeting",
                "duration"          => 120,
                "password"          => "123456",
                "alternative_hosts" => "hhonggil007@gmail.com",
                "settings"          => [
                    "approval_type"      => 2,
                    "audio"              => "telephony",
                    "contact_email"      => "hhonggil007@gmail.com",
                    "contact_name"       => "Jill Chill",
                    "email_notification" => true,
                    "host_video"         => true,
                    "participant_video"  => true,
                    "join_before_host"   => true,
                    "waiting_room"       => false,
                ],
                "start_time"        => $date,
                "timezone"          => "Asia/Jakarta",
                "topic"             => "My Meeting",
                "type"              => 2,
            ]);
            dd($response->collect());
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}
