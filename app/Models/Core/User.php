<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Pengadaan\Entities\Unit;
use Modules\Rapat\Entities\Pegawai;
use Modules\Rapat\Entities\RapatAgenda;
use Modules\Rapat\Entities\RapatTindakLanjut;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $connection = 'mysql';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'unit',
        'staff',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function token()
    {
        return $this->hasOne(OauthToken::class);
    }

    public function adminlte_image()
    {
        if (! \Storage::exists('/path/to/your/directory')) {
            return asset('/assets/img/avatar.png');
        } else {
            return asset('storage/assets/img/avatar/' . $this->avatar);
        }
    }

    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }

    public function adminlte_profile_url()
    {
        return 'users/profile';
    }

    public function avatar()
    {
        return 'avatar.jpg';
    }

    public function getUnit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit');
    }

    public function getStaff()
    {
        return $this->hasOne(Staff::class, 'id', 'staff');
    }

    public function hasRoleAktif($roleCheck)
    {
        $rol = $this->roles->pluck('name')->toArray();

        if (count($rol) < $this->role_aktif) {
            $this->role_aktif = 0;
            $this->save();
            return false;
        }
        if ($rol[$this->role_aktif] == $roleCheck) {
            return true;
        }

        return false;
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'username', 'username');
    }
    public function rapatAgenda()
    {
        return $this->hasMany(RapatAgenda::class, 'user_id');
    }
    public function rapatAgendaPimpinan()
    {
        return $this->hasMany(RapatAgenda::class, 'pimpinan_id');
    }
    public function rapatAgendaNotulis()
    {
        return $this->hasMany(RapatAgenda::class, 'notulis_id');
    }
    public function rapatAgendaPeserta()
    {
        return $this->belongsToMany(RapatAgenda::class, 'rapat_pesertas')->withPivot('id', 'status', 'is_penugasan');
    }
    public function rapatTindakLanjut()
    {
        return $this->hasMany(RapatTindakLanjut::class, 'user_id');
    }
    // public function kepanitiaans()
    // {
    //     return $this->belongsToMany(Kepanitiaan::class, 'kepanitiaan_user');
    // }
}
