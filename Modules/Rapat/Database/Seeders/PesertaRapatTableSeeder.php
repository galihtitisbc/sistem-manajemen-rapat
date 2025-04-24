<?php

namespace Modules\Rapat\Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Helper\StatusPesertaRapat;

class PesertaRapatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $users = User::all();
        RapatAgenda::factory(2)->create();
        $status = [StatusPesertaRapat::BERSEDIA->value, StatusPesertaRapat::TIDAK_BERSEDIA->value, StatusPesertaRapat::HADIR->value, StatusPesertaRapat::TIDAK_HADIR->value, StatusPesertaRapat::MENUNGGU->value];
        RapatAgenda::each(function ($rapatAgenda) use ($users, $status) {
            $pivotArray = [];
            $userIds    = $users->random(rand(2, 7))->pluck('id')->toArray();
            foreach ($userIds as $userId) {
                $pivotArray[] = ['user_id' => $userId, 'status' => $status[rand(0, 4)], 'is_penugasan' => rand(0, 1)];
            }
            $rapatAgenda->rapatAgendaPeserta()->attach($pivotArray);
        });
    }
}
