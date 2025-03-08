<?php
namespace Modules\Rapat\Http\Service\Implementation;

use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Service\RapatServiceInterface;
use Modules\Rapat\Http\Service\ZoomServiceInterface;

class RapatServiceImpl implements RapatServiceInterface
{
    private $zoomService;
    public function __construct(ZoomServiceInterface $zoomService)
    {
        $this->zoomService = $zoomService;
    }
    public function store(array $data)
    {
        try {
            $agendaRapat = RapatAgenda::create([
                'user_id'       => $data['user_id'],
                'pimpinan_id'   => $data['pimpinan_id'],
                'notulis_id'    => $data['notulis_id'],
                'nomor_surat'   => $data['nomor_surat'],
                'slug'          => $data['nomor_surat'],
                'waktu_mulai'   => $data['waktu_mulai'],
                'waktu_selesai' => $data['waktu_selesai'],
                'agenda_rapat'  => $data['agenda_rapat'],
                'tempat'        => $data['tempat'],
                'status'        => 'SCHEDULED',
                'calendar_link' => 'lorem ipsum',
                // 'lampiran'      => $data['lampiran'],
            ]);
            $agendaRapat->rapatAgendaPeserta()->attach($data['peserta_rapat']);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
        // $this->zoomService->createMeeting($data);
    }
    public function test()
    {
        dd('test');
    }
}
