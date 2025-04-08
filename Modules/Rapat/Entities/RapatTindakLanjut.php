<?php

namespace Modules\Rapat\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RapatTindakLanjut extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // protected static function newFactory()
    // {
    //     return \Modules\Rapat\Database\factories\RapatTindakLanjutFactory::new();
    // }
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
