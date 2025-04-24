<?php

namespace Modules\Rapat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RapatNotulenFile extends Model
{
    use HasFactory;

    protected $fillable = [];

    // protected static function newFactory()
    // {
    //     return \Modules\Rapat\Database\factories\RapatNotulenFileFactory::new();
    // }
    public function rapatNotulen()
    {
        return $this->belongsTo(RapatNotulen::class, 'rapat_notulen_id');
    }
}
