<?php

namespace Modules\Rapat\Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            'name'       => 'Pimpinan',
            'username'   => 'pimpinan',
            'email'      => 'pimpinan@gmail.com',
            'password'   => Hash::make('password'),
            'role_aktif' => 'pimpinan',
            'unit'       => 0,
            'staff'      => 0,
            'status'     => 2,
        ]);
        $pimpinan->assignRole(['pimpinan']);

        $pejabat = User::create([
            'name'       => 'Pejabat User',
            'username'   => 'pejabat',
            'email'      => 'pejabat@gmail.com',
            'password'   => Hash::make('password'),
            'role_aktif' => 'pejabat',
            'unit'       => 0,
            'staff'      => 0,
            'status'     => 2,
        ]);
        $pejabat->assignRole('pejabat');

        $pejabat = User::create([
            'username'   => 'sekretaris',
            'name'       => 'Sekretaris User',
            'email'      => 'sekretaris@gmail.com',
            'password'   => Hash::make('password'),
            'role_aktif' => 'sekretaris',
            'unit'       => 0,
            'staff'      => 0,
            'status'     => 2,
        ]);
        $pejabat->assignRole('sekretaris');

        $kepegawaian = User::create([
            'username'   => 'kepegawaian',
            'name'       => 'Kepegawaian User',
            'email'      => 'kepegawaian@gmail.com',
            'password'   => Hash::make('password'),
            'role_aktif' => 'kepegawaian',
            'unit'       => 0,
            'staff'      => 0,
            'status'     => 2,
        ]);
        $kepegawaian->assignRole('kepegawaian');

        $dosen = User::create([
            'username'   => 'dosen',
            'name'       => 'Dosen User',
            'email'      => 'dosen@gmail.com',
            'password'   => Hash::make('password'),
            'role_aktif' => 'dosen',
            'unit'       => 0,
            'staff'      => 0,
            'status'     => 2,
        ]);
        $dosen->assignRole('dosen');

        $multi = User::create([
            'username'   => 'multi',
            'name'       => 'Multi User',
            'email'      => 'multi@gmail.com',
            'password'   => Hash::make('password'),
            'role_aktif' => 'pejabat,dosen',
            'unit'       => 0,
            'staff'      => 0,
            'status'     => 2,
        ]);
        $multi->assignRole(['pejabat', 'dosen']);
        $roles       = ['pimpinan', 'pejabat', 'sekretaris', 'kepegawaian', 'dosen'];
        $pegawaiRecords = DB::table('pegawais')->get();
        foreach ($pegawaiRecords as $pegawai) {
            $randomRole = $roles[array_rand($roles)];

            $email = $pegawai->username . '@example.com';

            $user = User::create([
                'username'   => $pegawai->username ?? 'Tidak Ada Username',
                'name'       => $randomRole . ' ' . $pegawai->nama,
                'email'      => $email,
                'password'   => Hash::make('password'),
                'role_aktif' => $randomRole,
                'unit'       => $pegawai->jurusan ?? 0,
                'staff'      => $pegawai->staff ?? 0,
                'status'     => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user->assignRole($randomRole);
        }
    }
}
