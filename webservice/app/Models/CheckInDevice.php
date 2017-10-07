<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\District;
use App\Models\Building;
use App\Models\CheckInType;
use App\Models\CheckInPosition;

class CheckInDevice extends Model
{
    protected $fillable = [
        'check_in_type_id',
        'check_in_position_id',
        'district_id',
        'building_id',
        'name',
        'mac_address',
        'is_connected_to_elevator',
        'status',
    ];

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function building() {
        return $this->belongsTo(Building::class);
    }

    public function checkInType() {
        return $this->belongsTo(CheckInType::class);
    }

    public function checkInPosition() {
        return $this->belongsTo(CheckInPosition::class);
    }
}
