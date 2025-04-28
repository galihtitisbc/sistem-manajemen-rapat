<?php

namespace Modules\Rapat\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class RapatTindakLanjut extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // protected static function newFactory()
    // {
    //     return \Modules\Rapat\Database\factories\RapatTindakLanjutFactory::new();
    // }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($rapatTindakLanjut) {
            $rapatTindakLanjut->slug = static::generateUniqueSlug($rapatTindakLanjut->deskripsi_tugas);
        });

        static::updating(function ($rapatTindakLanjut) {
            if ($rapatTindakLanjut->isDirty('deskripsi_tugas')) {
                $rapatTindakLanjut->slug = static::generateUniqueSlug($rapatTindakLanjut->deskripsi_tugas, $rapatTindakLanjut->id);
            }
        });
    }

    public function scopeUserHaveTugas($query, $user, $rapatAgenda)
    {
        $query->when($rapatAgenda->pimpinan_id == $user->id || $rapatAgenda->user_id == $user->id || $rapatAgenda->notulis_id == $user->id, function ($q) use ($rapatAgenda) {
            $q->where('rapat_agenda_id', $rapatAgenda->id);
        }, function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        return $query;
    }

    public function scopeListAgendaRapatHaveTugas($query, $userId)
    {
        $query->whereHas('rapatAgenda', function ($q) use ($userId) {
            $q->where('pimpinan_id', $userId)
                ->orWhere('notulis_id', $userId);
        })
            ->orWhere('user_id', $userId);
        return $query;
    }
    public function rapatAgenda()
    {
        return $this->belongsTo(RapatAgenda::class, 'rapat_agenda_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function rapatTindakLanjutFile()
    {
        return $this->hasMany(RapatTindakLanjutFile::class, 'rapat_tindak_lanjut_id');
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
