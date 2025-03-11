<?php
namespace Modules\Rapat\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapatAgenda extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Rapat\Database\factories\RapatAgendaFactory::new ();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function rapatAgendaPimpinan()
    {
        return $this->belongsTo(User::class, 'pimpinan_id');
    }
    public function rapatAgendaNotulis()
    {
        return $this->belongsTo(User::class, 'notulis_id');
    }
    public function rapatAgendaPeserta()
    {
        return $this->belongsToMany(User::class, 'rapat_pesertas')->withPivot('status', 'is_penugasan');
    }
    public function rapatLampiran()
    {
        return $this->hasMany(RapatLampiran::class, 'rapat_agenda_id');
    }
    public function rapatDokumentasi()
    {
        return $this->hasMany(RapatDokumentasi::class, 'rapat_agenda_id');
    }
    public function rapatTindakLanjut()
    {
        return $this->hasMany(RapatTindakLanjut::class, 'rapat_agenda_id');
    }
}
