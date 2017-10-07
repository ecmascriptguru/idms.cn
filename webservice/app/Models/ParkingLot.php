<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Guard;

class ParkingLot extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contact',
        'phone'
    ];

    public function guards() {
        return $this->hasMany(Guard::class);
    }
}
