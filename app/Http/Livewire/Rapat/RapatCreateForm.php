<?php

namespace App\Http\Livewire\Rapat;

use App\Models\Core\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Requests\CreateRapatRequest;
use Modules\Rapat\Http\Service\Implementation\RapatService;

class RapatCreateForm extends Component
{
    use WithFileUploads;
    private $rapatService;
    public $nomorSurat;
    public $waktuMulai;
    public $waktuSelesai;
    public $agendaRapat;
    public $kepanitiaans;
    public $pesertaRapat;
    public $users;
    public $allUsers;
    public $pimpinanRapat;
    public $notulisRapat;
    public $lampiran = [];
    public $selectTempat;
    public $customTempat;
    public $cariPeserta;
    public $selectedKepanitiaan;
    public $pilihanWaktuSelesai;
    public function render()
    {
        return view('livewire.rapat.rapat-create-form',);
    }
    public function mount()
    {
        $this->pesertaRapat = collect();
        $this->users        = $this->allUsers;
    }
    protected function rules()
    {
        return (new CreateRapatRequest())->rules();
    }

    public function updatedWaktuMulai($value)
    {
        $this->waktuMulai   = Carbon::parse($value)->format('Y-m-d H:i:s');
        $this->pesertaRapat = $this->pesertaRapat->reject(function ($item) {
            return true;
        });
    }
    public function updatedWaktuSelesai($value)
    {
        $this->waktuSelesai = Carbon::parse($value)->format('Y-m-d H:i:s');
        $this->pesertaRapat = $this->pesertaRapat->reject(function ($item) {
            return true;
        });
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
    public function storeRapat()
    {
        $data = [
            'user_id'        => Auth::user()->id,
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
        $validatedData = (new CreateRapatRequest())->validated($data);
        try {
            $this->getRapatService()->store($validatedData);
            FlashMessage::success('Agenda Rapat Berhasil DiBuat');
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
