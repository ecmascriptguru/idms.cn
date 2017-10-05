<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\HouseType;

class FeeStandard extends Model
{
    protected $fillable = [
        'district_id',
        'house_type_id',
        'property_management_fee',
        'water_fee',
        'electricity_fee',
        'parking_fee',
        'gas_fee',
        'heating_fee',
        'internet_fee',
        'custom_fee_1_type_id',
        'custom_fee_1_name',
        'custom_fee_1_rate',
        'custom_fee_2_type_id',
        'custom_fee_2_name',
        'custom_fee_2_rate',
        'custom_fee_3_type_id',
        'custom_fee_3_name',
        'custom_fee_3_rate',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function houseType()
    {
        return $this->belongsTo(HouseType::class);
    }
}
