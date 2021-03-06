<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OperatingCompany;
use App\Models\Role;
use App\Models\District;
use App\User;

class PropertyCompany extends Model
{
    protected $fillable = [
        'operating_company_id',
        'name',
        'short_name',
        'address',
        'phone',
        'contact'
    ];

    public function users()
    {
        $role = Role::find(3);
        return User::where(['role_id' => $role->id, 'property_company_id' => $this->id])->get();
    }

    public function operatingCompany() {
        return $this->belongsTo(OperatingCompany::class);
    }

    public function districts() {
        return $this->hasMany(District::class);
    }
}
