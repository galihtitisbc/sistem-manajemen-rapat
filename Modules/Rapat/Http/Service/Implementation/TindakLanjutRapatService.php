<?php

namespace Modules\Rapat\Http\Service\Implementation;

use App\Models\Core\User;
use Illuminate\Support\Facades\DB;
use Modules\Rapat\Entities\RapatAgenda;
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
}
