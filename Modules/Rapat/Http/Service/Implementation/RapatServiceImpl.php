<?php
namespace Modules\Rapat\Http\Service\Implementation;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Service\RapatServiceInterface;
use Modules\Rapat\Jobs\CreateMeetingZoom;

class RapatServiceImpl implements RapatServiceInterface
{
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
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
            ]);
            if (isset($data['lampiran'])) {
                $namaLampiran = [];
                foreach ($data['lampiran'] as $index => $lampiran) {
                    $fileName = time() . "_{$index}_" . $lampiran->getClientOriginalName();
                    Storage::putFileAs('rapat', $lampiran, $fileName);
                    $namaLampiran[] = [
                        'nama_file' => $fileName,
                    ];
                }
                $agendaRapat->rapatLampiran()->createMany($namaLampiran);
            }
            if ($data['tempat'] == 'zoom') {
                CreateMeetingZoom::dispatch($agendaRapat);
            }
            $agendaRapat->rapatAgendaPeserta()->attach($data['peserta_rapat']);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception("Gagal Membuat Agenda Rapat : " . $e->getMessage());
        }
    }
}
