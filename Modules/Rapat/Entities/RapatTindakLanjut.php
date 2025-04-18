<?php

namespace Modules\Rapat\Entities;

use App\Models\Core\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Rapat\Http\Helper\StatusTindakLanjut;

class RapatTindakLanjut extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id'];

    // protected static function newFactory()
    // {
    //     return \Modules\Rapat\Database\factories\RapatTindakLanjutFactory::new();
    // }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'deskripsi_tugas',
            ],
        ];
    }

    public function scopeUserHaveTugas($query, $user, $rapatAgenda)
    {
        $query->when($rapatAgenda->pimpinan_id == $user->id || $rapatAgenda->user_id == $user->id, function ($q) use ($rapatAgenda) {
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
}
