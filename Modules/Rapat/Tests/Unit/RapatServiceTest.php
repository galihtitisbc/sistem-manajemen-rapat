<?php
namespace Modules\Rapat\Tests\Unit;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Http\Requests\CreateRapatRequest;
use Modules\Rapat\Http\Service\Implementation\RapatServiceImpl;
use Modules\Rapat\Jobs\CreateMeetingZoom;
use Tests\TestCase;

class RapatServiceTest extends TestCase
{
    protected $service;
    protected $createRapatRequest;
    public function setUp(): void
    {
        parent::setUp();
        $this->service            = Mockery::mock(RapatServiceImpl::class);
        $this->createRapatRequest = new CreateRapatRequest();
        Bus::fake();
        Storage::fake('rapat');
    }
    public function create_agenda_rapat_successful()
    {
        $data = [
            'user_id'       => 1,
            'pimpinan_id'   => 2,
            'notulis_id'    => 3,
            'nomor_surat'   => '123',
            'waktu_mulai'   => '2022-01-01 10:00',
            'waktu_selesai' => '2022-01-01 11:00',
            'agenda_rapat'  => 'Test Agenda',
            'tempat'        => 'zoom',
            'peserta_rapat' => [1, 2, 3],
        ];
        $this->createRapatRequest->merge($data);
        $validatedData = $this->createRapatRequest->validate($this->createRapatRequest->rules());
        $this->service->shouldReceive('store')->once()->with($validatedData)->andReturn(new RapatAgenda());
        $this->service->store($this->createRapatRequest->validated());
        Bus::assertDispatched(CreateMeetingZoom::class);
    }
}
