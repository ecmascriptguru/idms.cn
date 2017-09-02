<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Role;
use App\Models\OperatingCompany;
use App\Models\PropertyCompany;
use App\Models\District;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'username', 
        'password', 
        'phone', 
        'address', 
        'role_id', 
        'operating_company_id', 
        'property_company_id', 
        'district_id', 
        'organization_id'
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

        if ($role->id == 2) {
            return $this->belongsTo(OperatingCompany::class, 'operating_company_id');
        } elseif ($role->id == 3) {
            return $this->belongsTo(PropertyCompany::class, 'property_company_id');
        } elseif ($role->id == 4) {
            return $this->belongsTo(District::class, 'district_id');
        } else {
            return $this->belongsTo(OperatingCompany::class, 'operating_company_id');
        }
    }

    public function operatingCompany() {
        return $this->belongsTo(OperatingCompany::class);
    }

    public function propertyCompany() {
        return $this->belongsTo(PropertyCompany::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }
}
