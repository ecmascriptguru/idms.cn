<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Guard;

class Lane extends Model
{
    protected $fillable = [
        'parking_lot_id',
        'guard_id',
        'name',
        'number',
        'control_number',
    ];

    public function guard() {
        return $this->belongsTo(Guard::class);
    }
}
