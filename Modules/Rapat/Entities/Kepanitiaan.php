<?php

namespace Modules\Rapat\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kepanitiaan extends Model
{
    use HasFactory;

    protected $fillable = [];

    // protected static function newFactory()
    // {
    //     return \Modules\Rapat\Database\factories\KepanitiaanFactory::new();
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'kepanitiaan_user');
    }
}
