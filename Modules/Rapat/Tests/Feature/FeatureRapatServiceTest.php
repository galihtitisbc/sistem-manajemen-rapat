<?php
namespace Modules\Rapat\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Modules\Rapat\Http\Service\Implementation\RapatServiceImpl;
use Tests\TestCase;

class FeatureRapatServiceTest extends TestCase
{
    protected function setUp(): void
    {
        Queue::fake();
    }
    public function test_create_agenda_rapat_with_zoom_success()
    {
        $mockService   = Mockery::mock(RapatServiceImpl::class);
        $fakeFile      = UploadedFile::fake()->create('test.pdf', 2000);
        $validatedData = [
            'user_id'       => 1,
            'pimpinan_id'   => 2,
            'notulis_id'    => 3,
            'nomor_surat'   => 'ABC-123',
            'waktu_mulai'   => now()->addHour()->format('Y-m-d H:i:s'),
            'waktu_selesai' => now()->addHours(2)->format('Y-m-d H:i:s'),
            'agenda_rapat'  => 'Diskusi strategi bisnis',
            'tempat'        => 'zoom',
            'peserta_rapat' => [4, 5, 6],
            'lampiran'      => [$fakeFile],
        ];
        $mockService->shouldReceive('store')
            ->once()
            ->with($validatedData)
            ->andReturn(true);
        $this->assertTrue($mockService->store($validatedData));
    }
}
