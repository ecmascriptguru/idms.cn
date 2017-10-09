<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ParkingLot;
use App\Models\Guard as ParkingGuard;

class Lane extends Model
{
    protected $fillable = [
        'parking_lot_id',
        'guard_id',
        'name',
        'number',
        'control_number',
    ];

    public function parkingGuard() {
        return parkingGuard::find($this->guard_id);
    }

    public function parkingLot() {
        return $this->belongsTo(ParkingLot::class);
    }
}
