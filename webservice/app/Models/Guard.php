<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ParkingLot;

class Guard extends Model
{
    protected $fillable = [
        'parking_lot_id',
        'name',
    ];

    public function parkingLot() {
        return $this->belongsTo(ParkingLot::class);
    }
}
