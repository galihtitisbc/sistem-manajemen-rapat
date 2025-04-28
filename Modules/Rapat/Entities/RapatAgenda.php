<?php

namespace Modules\Rapat\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Rapat\Http\Helper\StatusTindakLanjut;
use Illuminate\Support\Str;

class RapatAgenda extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Rapat\Database\factories\RapatAgendaFactory::new();
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($rapatAgenda) {
            $rapatAgenda->slug = static::generateUniqueSlug($rapatAgenda->agenda_rapat);
        });

        static::updating(function ($rapatAgenda) {
            if ($rapatAgenda->isDirty('agenda_rapat')) {
                $rapatAgenda->slug = static::generateUniqueSlug($rapatAgenda->agenda_rapat, $rapatAgenda->id);
            }
        });
    }
    public function scopeUserIsPesertaOrCreator($query, $userId)
    {
        $query->whereHas('rapatAgendaPeserta', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->orWhere('user_id', $userId);
        return $query;
    }
    // public function scopeShowTindakLanjut($query, $userId)
    // {
    //     $query->whereHas('rapatTindakLanjut', function ($q) use ($userId) {
    //         $q->where('user_id', $userId);
    //     })
    //         ->orWhere('pimpinan_id', $userId)
    //         ->orWhere(function ($q) use ($userId) {
    //             $q->where('user_id', $userId)
    //                 ->whereHas('rapatTindakLanjut');
    //         });

    //     return $query;
    // }
    public function getStatusTindakLanjutAttribute()
    {
        if ($this->rapatTindakLanjut->contains('status', StatusTindakLanjut::BELUM_SELESAI->value)) {
            return StatusTindakLanjut::BELUM_SELESAI->value;
        }
        return StatusTindakLanjut::SELESAI->value;
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
    public function rapatKepanitiaan()
    {
        return $this->belongsTo(Kepanitiaan::class, 'kepanitiaan_id');
    }
    public function rapatNotulen()
    {
        return $this->hasOne(RapatNotulen::class, 'rapat_agenda_id');
    }
    private static function generateUniqueSlug($judul, $ignoreId = null)
    {
        $slug = Str::slug($judul);
        $originalSlug = $slug;
        $count = 1;
        while (static::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
