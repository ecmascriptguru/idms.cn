<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\District;
use App\Models\Building;
use App\Models\HouseType;

class Flat extends Model
{
    protected $fillable = [
        'district_id',
        'building_id',
        'house_type_id',
        'name',
        'number',
        'area',
        'owner_1_name',
        'owner_1_document_type',
        'owner_1_document_number',
        'owner_1_phone',
        'owner_2_name',
        'owner_2_document_type',
        'owner_2_document_number',
        'owner_2_phone',

    ];

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function building() {
        return $this->belongsTo(Building::class);
    }

    public function houseType() {
        return $this->belongsTo(HouseType::class);
    }
}
