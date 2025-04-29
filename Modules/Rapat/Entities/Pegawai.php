<?php

namespace Modules\Rapat\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // protected static function newFactory()
    // {
    //     return \Modules\Rapat\Database\factories\PegawaiFactory::new();
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
