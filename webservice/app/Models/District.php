<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OperatingCompany;
use App\Models\PropertyCompany;
use App\Models\ParkingLot;
use App\Models\Role;
use App\User;

class District extends Model
{
    protected $fillable = [
        'operating_company_id',
        'property_company_id',
        'name',
        'short_name',
        'province',
        'city',
        'address',
        'phone',
        'contact',
        'parking_lot_id',
        'reminder',
    ];

    public function users()
    {
        $role = Role::find(4);
        return User::where(['role_id' => $role->id, 'district_id' => $this->id])->get();
    }

    public function operatingCompany() {
        return $this->belongsTo(OperatingCompany::class);
    }

    public function propertyCompany() {
        return $this->belongsTo(PropertyCompany::class);
    }

    public function parkingLot() {
        return $this->belongsTo(ParkingLot::class);
    }
}
