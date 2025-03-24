<?php
namespace Modules\Rapat\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Modules\Rapat\Http\Requests\CreateRapatRequest;
use Tests\TestCase;

class RapatServiceTest extends TestCase
{
    protected $service;
    protected $createRapatRequest;

    protected function validateData(array $data)
    {
        return Validator::make($data, (new CreateRapatRequest())->rules());
    }

    public function test_valid_data_passes_validation()
    {
        $validData = [
            'user_id'        => 1,
            'pimpinan_id'    => 2,
            'kepanitiaan_id' => null,
            'peserta_rapat'  => [3, 4, 5],
            'notulis_id'     => 6,
            'nomor_surat'    => 'ABC-123',
            'waktu_mulai'    => '2025-03-22 10:00:00',
            'waktu_selesai'  => '2025-03-22 12:00:00',
            'agenda_rapat'   => 'Pembahasan strategi bisnis',
            'tempat'         => 'Ruang Meeting A',
        ];
        $validator = $this->validateData($validData);
        $this->assertFalse($validator->fails());
    }

    public function test_missing_required_fields_fail_validation()
    {
        $invalidData = [
            'user_id'     => null,
            'pimpinan_id' => null,
        ];

        $validator = $this->validateData($invalidData);
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('user_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('pimpinan_id', $validator->errors()->toArray());
    }

    public function test_waktu_selesai_before_waktu_mulai_fails_validation()
    {
        $invalidData = [
            'user_id'       => 1,
            'pimpinan_id'   => 2,
            'peserta_rapat' => [3, 4],
            'notulis_id'    => 6,
            'nomor_surat'   => 'ABC-123',
            'waktu_mulai'   => '2025-03-22 12:00:00',
            'waktu_selesai' => '2025-03-22 10:00:00',
            'agenda_rapat'  => 'Evaluasi bulanan',
            'tempat'        => 'Ruang Rapat',
        ];

        $validator = $this->validateData($invalidData);
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('waktu_selesai', $validator->errors()->toArray());
    }

    public function test_invalid_lampiran_format_fails_validation()
    {
        $fakeFile    = UploadedFile::fake()->create('test.exe', 2000);
        $invalidData = [
            'user_id'       => 1,
            'pimpinan_id'   => 2,
            'peserta_rapat' => [3, 4],
            'notulis_id'    => 6,
            'nomor_surat'   => 'ABC-123',
            'waktu_mulai'   => '2025-03-22 10:00:00',
            'waktu_selesai' => '2025-03-22 12:00:00',
            'agenda_rapat'  => 'Evaluasi bulanan',
            'tempat'        => 'Ruang Rapat',
            'lampiran'      => [$fakeFile],
        ];
        $validator = $this->validateData($invalidData);
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('lampiran.0', $validator->errors()->toArray());
    }
}
