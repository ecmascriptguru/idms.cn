<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Role;
use App\Models\OperatingCompany;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password', 'phone', 'address', 'role_id', 'organization_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->id;
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function organization() {
        $role = $this->role;

        if ($role->id == 1) {
            return $this->belongsTo(OperatingCompany::class, 'organization_id');
        } elseif ($role->id == 2) {
            return $this->belongsTo(OperatingCompany::class, 'organization_id');
        }
    }
}
