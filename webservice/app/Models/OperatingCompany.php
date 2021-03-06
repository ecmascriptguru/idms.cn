<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyCompany;
use App\Models\Role;
use App\Models\District;
use App\User;

class OperatingCompany extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'address',
        'phone',
        'contact'
    ];

    public function users()
    {
        $role = Role::find(2);
        return User::where(['role_id' => $role->id, 'operating_company_id' => $this->id])->get();
    }

    public function propertyCompanies() {
        return $this->hasMany(PropertyCompany::class);
    }
    
    public function districts() {
        return $this->hasMany(District::class);
    }
}
