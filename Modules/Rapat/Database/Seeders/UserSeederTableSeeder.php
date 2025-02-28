<?php

namespace Modules\Rapat\Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $pimpinan = User::create([
            'name' => 'Pimpinan',
            'username' => 'pimpinan',
            'email' => 'pimpinan@gmail.com',
            'password' => Hash::make('password'),
            'role_aktif' => 'pimpinan',
            'unit' => 0,
            'staff' => 0,
            'status' => 2
        ]);
        $pimpinan->assignRole(['pimpinan']);

        $pejabat = User::create([
            'name' => 'Pejabat User',
            'username' => 'pejabat',
            'email' => 'pejabat@gmail.com',
            'password' => Hash::make('password'),
            'role_aktif' => 'pejabat',
            'unit' => 0,
            'staff' => 0,
            'status' => 2
        ]);
        $pejabat->assignRole('pejabat');

        $pejabat = User::create([
            'username' => 'sekretaris',
            'name' => 'Sekretaris User',
            'email' => 'sekretaris@gmail.com',
            'password' => Hash::make('password'),
            'role_aktif' => 'sekretaris',
            'unit' => 0,
            'staff' => 0,
            'status' => 2
        ]);
        $pejabat->assignRole('sekretaris');

        $kepegawaian = User::create([
            'username' => 'kepegawaian',
            'name' => 'Kepegawaian User',
            'email' => 'kepegawaian@gmail.com',
            'password' => Hash::make('password'),
            'role_aktif' => 'kepegawaian',
            'unit' => 0,
            'staff' => 0,
            'status' => 2
        ]);
        $kepegawaian->assignRole('kepegawaian');

        $dosen = User::create([
            'username' => 'dosen',
            'name' => 'Dosen User',
            'email' => 'dosen@gmail.com',
            'password' => Hash::make('password'),
            'role_aktif' => 'dosen',
            'unit' => 0,
            'staff' => 0,
            'status' => 2
        ]);
        $dosen->assignRole('dosen');

        $multi = User::create([
            'username' => 'multi',
            'name' => 'Multi User',
            'email' => 'multi@gmail.com',
            'password' => Hash::make('password'),
            'role_aktif' => 'pejabat,dosen',
            'unit' => 0,
            'staff' => 0,
            'status' => 2
        ]);
        $multi->assignRole(['pejabat', 'dosen']);
    }
}
