<?php
namespace Modules\Rapat\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kepanitiaan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // protected static function newFactory()
    // {
    //     return \Modules\Rapat\Database\factories\KepanitiaanFactory::new();
    // }
    public function scopePegawaiIsAnggotaPanitia($query, $username)
    {
        $query->whereHas('pegawai', function ($q) use ($username) {
            $q->where('username', $username);
        });
        return $query;
    }
    public function pegawai()
    {
        return $this->belongsToMany(Pegawai::class, 'kepanitiaan_pegawai', 'kepanitiaan_id', 'pegawai_username', 'id', 'username');
    }
    public function rapatAgenda()
    {
        return $this->hasMany(RapatAgenda::class, 'kepanitiaan_id');
    }
    public function ketua()
    {
        return $this->belongsTo(Pegawai::class, 'pimpinan_username', 'username');
    }
}
