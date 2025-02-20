<?php

namespace Modules\Rapat\Database\Seeders;

use App\Models\Core\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MenuRapatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Menu::where('modul', 'Rapat')->delete();
        $menu =  Menu::create([
            'modul' => 'Rapat',
            'label' => 'Rapat',
            'url' => 'rapat',
            'can' => serialize(['terdaftar']),
            'icon' => 'far fa-circle',
            'urut' => 1,
            'parent_id' => 0,
            'active' => serialize(['rapat', 'rapat*']),
        ]);
        // $this->call("OthersTableSeeder");
    }
}
