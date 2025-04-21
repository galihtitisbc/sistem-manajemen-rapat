<?php

namespace Modules\Rapat\Http\Service\Implementation;

use App\Models\Core\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Entities\RapatTindakLanjut;
use Modules\Rapat\Http\Helper\StatusTindakLanjut;
use Modules\Rapat\Http\Requests\CreateTugasPesertaRapatRequest;

class TindakLanjutRapatService
{
    public function createTugasPesertaRapat(RapatAgenda $rapatAgenda, User $user, CreateTugasPesertaRapatRequest $request)
    {
        try {
            DB::beginTransaction();
            $rapatAgenda->rapatTindakLanjut()->create([
                'user_id'           =>  $user->id,
                'deskripsi_tugas'   =>  $request->deskripsi,
                'batas_waktu'       =>  $request->batas_waktu
            ]);
            $rapatAgenda->rapatAgendaPeserta()->syncWithoutDetaching([
                $user->id => ['is_penugasan' => 1]
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function uploadTugas($tindakLanjutRapat, $request)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('file_tugas')) {
                $fileTugas = [];
                foreach ($request->file('file_tugas') as $index => $fileTugas) {
                    $fileName = time() . "_{$index}_" . $fileTugas->getClientOriginalName();
                    Storage::putFileAs('tindakLanjut', $fileTugas, $fileName);
                    $namafileTugas[] = [
                        'nama_file' => $fileName,
                    ];
                }
                $tindakLanjutRapat->rapatTindakLanjutFile()->createMany($namafileTugas);
            }
            $tindakLanjutRapat->update([
                'status'            => StatusTindakLanjut::SELESAI->value,
                'tugas'             =>  $request->tugas,
                'kendala'           => $request->kendala,
                'tanggal_selesai'   => now()
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
        }
    }
    public function editTugas($tindakLanjutRapat, $request)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('file_tugas')) {
                if ($tindakLanjutRapat->rapatTindakLanjutFile->isNotEmpty()) {
                    foreach ($tindakLanjutRapat->rapatTindakLanjutFile as $file) {
                        Storage::delete('tindakLanjut/' . $file->nama_file);
                    }
                    $tindakLanjutRapat->rapatTindakLanjutFile()->delete();
                }
                $fileTugas = [];
                foreach ($request->file('file_tugas') as $index => $fileTugas) {
                    $fileName = time() . "_{$index}_" . $fileTugas->getClientOriginalName();
                    Storage::putFileAs('tindakLanjut', $fileTugas, $fileName);
                    $namafileTugas[] = [
                        'nama_file' => $fileName,
                    ];
                }
                $tindakLanjutRapat->rapatTindakLanjutFile()->createMany($namafileTugas);
            }
            $tindakLanjutRapat->update([
                'status'            => StatusTindakLanjut::SELESAI->value,
                'tugas'             =>  $request->tugas,
                'kendala'           => $request->kendala,
                'tanggal_selesai'   => now()
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
        }
    }
    public function simpanTugas($data)
    {
        try {
            DB::beginTransaction();
            $tindakLanjut = RapatTindakLanjut::where('slug', $data['slug'])->firstOrFail();
            $tindakLanjut->update([
                'penilaian' => $data['kriteria_penilaian'],
                'komentar' => $data['komentar_penugasan'],
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }
}
