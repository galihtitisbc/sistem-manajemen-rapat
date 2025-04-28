<?php

namespace App\Http\Livewire\Rapat;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Requests\UpdateRapatRequest;
use Modules\Rapat\Http\Service\Implementation\RapatService;

class RapatEditForm extends Component
{
    use WithFileUploads;
    public $agendaRapatLoad;
    private $rapatService;
    public $nomorSurat;
    public $waktuMulai;
    public $waktuSelesai;
    public $kepanitiaans;
    public $pesertaRapat;
    public $agendaRapat;
    public $users;
    public $allUsers;
    public $pimpinanRapat;
    public $notulisRapat;
    public $lampiran = [];
    public $lampiranOld;
    public $selectTempat;
    public $customTempat;
    public $cariPeserta;
    public $selectedKepanitiaan;
    public $pilihanWaktuSelesai;

    public function render()
    {
        return view('livewire.rapat.rapat-edit-form');
    }
    public function mount()
    {
        $this->users               = $this->allUsers;
        $this->pesertaRapat        = $this->agendaRapatLoad->rapatAgendaPeserta->collect();
        $this->nomorSurat          = $this->agendaRapatLoad->nomor_surat;
        $this->waktuMulai          = $this->agendaRapatLoad->waktu_mulai;
        $this->waktuSelesai        = $this->agendaRapatLoad->waktu_selesai;
        $this->selectTempat        = $this->agendaRapatLoad->tempat == 'zoom' ? 'zoom' : 'custom';
        $this->customTempat        = $this->agendaRapatLoad->tempat == 'zoom' ? '' : $this->agendaRapatLoad->tempat;
        $this->agendaRapat         = $this->agendaRapatLoad->agenda_rapat;
        $this->selectedKepanitiaan = $this->agendaRapatLoad->kepanitiaan_id;
        $this->pimpinanRapat       = $this->agendaRapatLoad->pimpinan_id;
        $this->notulisRapat        = $this->agendaRapatLoad->notulis_id;
        $this->lampiranOld         = $this->agendaRapatLoad->rapatLampiran->pluck('nama_file')->toArray();
        $this->pilihanWaktuSelesai = $this->agendaRapatLoad->waktu_selesai == null ? 'selesai' : 'manual';
    }
    protected function rules()
    {
        return (new UpdateRapatRequest())->rules();
    }
    public function updatedWaktuMulai($value)
    {
        $this->waktuMulai = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function updatedWaktuSelesai($value)
    {
        $this->waktuSelesai = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function updatedPilihanWaktuSelesai($value)
    {
        if ($value === 'selesai') {
            $this->waktuSelesai = "SELESAI";
        } else if ($value !== 'manual') {
            $this->waktuSelesai = Carbon::parse($this->waktuMulai)->addHour()->format('Y-m-d H:i:s');
        }
    }
    public function updatedCariPeserta($value)
    {
        if (empty($value)) {
            $this->users = $this->allUsers;
            return;
        }
        $this->users = $this->allUsers->filter(fn($user) => str_contains(strtolower($user->name), strtolower($value)));
    }
    public function updatingSelectedKepanitiaan($value)
    {
        $userKepanitiaan = '';
        if ($this->selectedKepanitiaan == null) {
            $userKepanitiaan = $this->kepanitiaans->where('id', $value)->first()->users;
        } else {
            $userKepanitiaan = $this->kepanitiaans->where('id', $this->selectedKepanitiaan)->first()->users;
        }
        $this->pesertaRapat = $this->pesertaRapat->reject(function ($item) use ($userKepanitiaan) {
            return $userKepanitiaan->contains('id', $item['id']);
        });
    }
    public function updatedSelectedKepanitiaan($value)
    {
        if ($value != null) {
            $userKepanitiaan = $this->kepanitiaans->where('id', $this->selectedKepanitiaan)->first()->users;
            foreach ($userKepanitiaan as $value) {
                $this->pesertaRapat->push($value);
            }
        }
    }
    // public function setSelectedKepanitiaan($kepanitiaanId)
    // {
    //     $this->selectedKepanitiaan = $kepanitiaanId;
    //     $userKepanitiaan           = $this->kepanitiaans->where('id', $kepanitiaanId)->first()->users;
    //     $this->pesertaRapat        = $this->pesertaRapat->reject(function ($item) {
    //         return true;
    //     });
    //     foreach ($userKepanitiaan as $value) {
    //         $this->pesertaRapat->push($value);
    //     }
    // }
    public function selectPesertaRapat($peserta, $isCheked)
    {
        if ($isCheked) {
            $this->pesertaRapat->push($peserta);
        } else {
            $this->pesertaRapat = $this->pesertaRapat->reject(function ($item) use ($peserta) {
                return $item['id'] == $peserta['id'];
            });
        }
    }
    public function updateRapat()
    {
        $data = [
            'pimpinan_id'    => $this->pimpinanRapat,
            'peserta_rapat'  => $this->pesertaRapat->pluck('id')->toArray(),
            'notulis_id'     => $this->notulisRapat,
            'kepanitiaan_id' => $this->selectedKepanitiaan,
            'nomor_surat'    => $this->nomorSurat,
            'waktu_mulai'    => $this->waktuMulai,
            'waktu_selesai'  => $this->waktuSelesai,
            'agenda_rapat'   => $this->agendaRapat,
            'tempat'         => $this->selectTempat == 'custom' ? $this->customTempat : $this->selectTempat,
            'lampiran'       => $this->lampiran,
        ];
        $validatedData = (new UpdateRapatRequest())->validated($data);
        try {
            $this->getRapatService()->update($validatedData, $this->agendaRapatLoad->id);
            FlashMessage::success('Agenda Rapat Berhasil Di Ubah');
            return redirect()->to('/rapat/agenda-rapat');
        } catch (\Throwable $e) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Gagal!',
                'text'  => 'Terjadi kesalahan: ' . $e->getMessage(),
                'icon'  => 'error',
            ]);
        }
    }
    public function getRapatService()
    {
        if (! $this->rapatService) {
            $this->rapatService = App::make(RapatService::class);
        }
        return $this->rapatService;
    }
}
