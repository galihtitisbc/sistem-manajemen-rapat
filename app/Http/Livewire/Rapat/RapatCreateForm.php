<?php

namespace App\Http\Livewire\Rapat;

use App\Models\Core\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Http\Requests\CreateRapatRequest;

class RapatCreateForm extends Component
{
    public $judulRapat;
    public $tempat;
    public $waktuMulai;
    public $waktuSelesai;
    public $deskripsi;
    public $kepanitiaans;
    public $kepanitiaanSelected;
    public $pesertaRapat;
    public $users;
    public $pimpinanRapat;
    public $notulisRapat;
    public function render()
    {
        return view('livewire.rapat.rapat-create-form');
    }
    public function mount()
    {
        $this->kepanitiaans = Kepanitiaan::all();
        $this->pesertaRapat = collect();
        $this->users = User::with('rapatAgendaPeserta')->get();
    }
    protected function rules()
    {
        return (new CreateRapatRequest())->rules();
    }
    public function updatedWaktuMulai($value)
    {
        $this->waktuMulai = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function updatedWaktuSelesai($value)
    {
        $this->waktuSelesai = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

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
            'judul_rapat'    => $this->judulRapat,
            'waktu_mulai'    => $this->waktuMulai,
            'waktu_selesai'  => $this->waktuSelesai,
            'deskripsi'      => $this->deskripsi,
            'tempat'         => $this->tempat,
            // 'lampiran'       => $this->lampiran,
        ];
        $validatedData = (new CreateRapatRequest())->validated($data);
        dd($validatedData);
    }
}
