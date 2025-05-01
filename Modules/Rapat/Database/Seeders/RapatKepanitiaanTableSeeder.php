<?php
namespace Modules\Rapat\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Entities\Pegawai;

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
        ];
        Kepanitiaan::insert($kepanitiaans);
        $pegawai = ['tefa', 'dimdim', 'jaeni', 'eka'];
        Kepanitiaan::each(function ($kepanitiaan) use ($pegawai) {
            $kepanitiaan->pegawai()->attach($pegawai);
        });
        $panitia = Kepanitiaan::create([
            'nama_kepanitiaan' => 'Panitia Konsumsi',
            'deskripsi'        => 'Panitia yang mengorganisir konsumsi tahunan',
            'tanggal_mulai'    => now()->subDays(20)->toDateString(),
            'tanggal_berakhir' => now()->addDays(5)->toDateString(),
            'tujuan'           => 'menyediakan konsumsi.',
            'status'           => 'AKTIF',
        ]);
        $pegawai = ['eka', 'haris', 'erna'];
        $panitia->pegawai()->attach($pegawai);
    }
}
