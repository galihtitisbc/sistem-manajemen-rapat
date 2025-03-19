<?php
namespace Modules\Rapat\Database\Seeders;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Rapat\Entities\Kepanitiaan;

class RapatKepanitiaanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $kepanitiaans = [
            [
                'nama_kepanitiaan' => 'Panitia Seminar Teknologi',
                'deskripsi'        => 'Panitia yang bertanggung jawab atas seminar teknologi tahunan.',
                'tanggal_mulai'    => now()->subDays(10)->toDateString(),
                'tanggal_berakhir' => now()->addDays(10)->toDateString(),
                'tujuan'           => 'Menyelenggarakan seminar teknologi bagi mahasiswa.',
                'status'           => 'AKTIF',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'nama_kepanitiaan' => 'Panitia Bakti Sosial',
                'deskripsi'        => 'Panitia yang mengorganisir kegiatan bakti sosial tahunan.',
                'tanggal_mulai'    => now()->subDays(20)->toDateString(),
                'tanggal_berakhir' => now()->addDays(5)->toDateString(),
                'tujuan'           => 'Membantu masyarakat dengan kegiatan sosial.',
                'status'           => 'AKTIF',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ];
        Kepanitiaan::insert($kepanitiaans);
        $users = User::whereBetween('id', [2, 7])->get();
        Kepanitiaan::each(function ($kepanitiaan) use ($users) {
            $kepanitiaan->users()->attach($users);
        });
        $panitia = Kepanitiaan::create([
            'nama_kepanitiaan' => 'Panitia Konsumsi',
            'deskripsi'        => 'Panitia yang mengorganisir konsumsi tahunan',
            'tanggal_mulai'    => now()->subDays(20)->toDateString(),
            'tanggal_berakhir' => now()->addDays(5)->toDateString(),
            'tujuan'           => 'menyediakan konsumsi.',
            'status'           => 'AKTIF',
        ]);
        $users = User::whereBetween('id', [17, 22])->get();
        $panitia->users()->attach($users);
    }
}
