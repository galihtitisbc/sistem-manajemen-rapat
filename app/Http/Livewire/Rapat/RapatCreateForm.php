<?php
namespace App\Http\Livewire\Rapat;

use App\Models\Core\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Http\Requests\CreateRapatRequest;
use Modules\Rapat\Http\Service\RapatServiceInterface;

class RapatCreateForm extends Component
{
    private $rapatService;
    public $nomorSurat;
    public $tempat;
    public $waktuMulai;
    public $waktuSelesai;
    public $agendaRapat;
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
        $this->users        = User::with('rapatAgendaPeserta')->get();
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
            'user_id'       => Auth::user()->id,
            'pimpinan_id'   => $this->pimpinanRapat,
            'peserta_rapat' => $this->pesertaRapat->pluck('id')->toArray(),
            'notulis_id'    => $this->notulisRapat,
            'nomor_surat'   => $this->nomorSurat,
            'waktu_mulai'   => $this->waktuMulai,
            'waktu_selesai' => $this->waktuSelesai,
            'agenda_rapat'  => $this->agendaRapat,
            'tempat'        => $this->tempat,
            // 'lampiran'       => $this->lampiran,
        ];
        $validatedData = (new CreateRapatRequest())->validated($data);
        try {
            $this->getRapatService()->store($validatedData);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
    public function getRapatService()
    {
        if (! $this->rapatService) {
            $this->rapatService = App::make(RapatServiceInterface::class);
        }
        return $this->rapatService;
    }
}
