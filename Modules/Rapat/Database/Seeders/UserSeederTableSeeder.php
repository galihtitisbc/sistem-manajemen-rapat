<?php
namespace Modules\Rapat\Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
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
        $customNames = ['Andi', 'Budi', 'Citra', 'Dewi', 'Yanto', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko', 'Kiki', 'Lina', 'Mira', 'Novi', 'Oscar'];

        for ($i = 0; $i < 15; $i++) {
            $role = $roles[array_rand($roles)];
            $name = $customNames[$i];

            $user = User::create([
                'username'   => strtolower($name),
                'name'       => $name . ' ' . ucfirst($role),
                'email'      => strtolower($name) . '@gmail.com',
                'password'   => Hash::make('password'),
                'role_aktif' => $role,
                'unit'       => 0,
                'staff'      => 0,
                'status'     => 2,
            ]);

            $user->assignRole($role);
        }

    }
}
