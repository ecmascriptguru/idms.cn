<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\District;
use App\Models\Building;
use App\Models\HouseType;
use App\Models\FeeStandard;

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

    public function feeStandard() {
        $feeStandards = FeeStandard::where(['district_id' => $this->district->id, 'house_type_id' => $this->houseType->id])->get();

        if ($feeStandards->count() > 0) {
            return $feeStandards[0];
        } else {
            return null;
        }
    }
}
