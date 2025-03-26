<?php
namespace Modules\Rapat\Http\Service\Implementation;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Helper\StatusAgendaRapat;
use Modules\Rapat\Jobs\CreateMeetingZoom;

class RapatService
{
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $agendaRapat = RapatAgenda::create([
                'user_id'        => $data['user_id'],
                'pimpinan_id'    => $data['pimpinan_id'],
                'notulis_id'     => $data['notulis_id'],
                'kepanitiaan_id' => $data['kepanitiaan_id'] == "" ? null : $data['kepanitiaan_id'],
                'nomor_surat'    => $data['nomor_surat'],
                'waktu_mulai'    => $data['waktu_mulai'],
                'waktu_selesai'  => $data['waktu_selesai'],
                'agenda_rapat'   => $data['agenda_rapat'],
                'tempat'         => $data['tempat'],
                'status'         => 'SCHEDULED',
                'calendar_link'  => 'lorem ipsum',
            ]);
            if (isset($data['lampiran'])) {
                //simpan lampiran ke storage
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
    public function ubahStatusAgendaRapat(RapatAgenda $agendaRapat)
    {
        try {
            if (StatusAgendaRapat::SCHEDULED->value == $agendaRapat->status) {
                $agendaRapat->status = StatusAgendaRapat::CANCELLED->value;
                $agendaRapat->save();
            } else {
                $agendaRapat->status = StatusAgendaRapat::SCHEDULED->value;
                $agendaRapat->save();
            }
        } catch (\Throwable $th) {
            throw new Exception("Gagal Mengubah Status Agenda Rapat : " . $th->getMessage());
        }
    }
    public function update(array $data, $agendaRapatId)
    {
        try {
            $agendaRapat = RapatAgenda::with('rapatLampiran')->where('id', $agendaRapatId)->firstOrFail();
            $oldTempat   = $agendaRapat->tempat;
            DB::beginTransaction();
            $agendaRapat->update([
                'pimpinan_id'    => $data['pimpinan_id'],
                'notulis_id'     => $data['notulis_id'],
                'kepanitiaan_id' => $data['kepanitiaan_id'] == "" ? null : $data['kepanitiaan_id'],
                'nomor_surat'    => $data['nomor_surat'],
                'waktu_mulai'    => $data['waktu_mulai'],
                'waktu_selesai'  => $data['waktu_selesai'],
                'agenda_rapat'   => $data['agenda_rapat'],
                'tempat'         => $data['tempat'],
                'calendar_link'  => 'lorem ipsum',
            ]);
            if (isset($data['lampiran'])) {
                //hapus lampiran lama
                if ($agendaRapat->rapatLampiran->isNotEmpty()) {
                    foreach ($agendaRapat->rapatLampiran as $lampiran) {
                        Storage::delete('rapat/' . $lampiran->nama_file);
                    }
                    $agendaRapat->rapatLampiran()->delete();
                }
                //upload lampiran
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
            if ($oldTempat != 'zoom' && $data['tempat'] == 'zoom') {
                CreateMeetingZoom::dispatch($agendaRapat);
            }
            $agendaRapat->rapatAgendaPeserta()->sync($data['peserta_rapat']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception("Gagal Mengubah Agenda Rapat : " . $th->getMessage());
        }
    }
}
