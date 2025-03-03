<?php

namespace Modules\Rapat\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Rapat\Entities\RapatAgenda;
use App\Models\Core\User;

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
        RapatAgenda::each(function ($rapatAgenda) use ($users) {
            $rapatAgenda->rapatAgendaPeserta()->attach($users->random(rand(2, 7))->pluck('id')->toArray());
        });
    }
}
