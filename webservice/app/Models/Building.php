<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\District;

class Building extends Model
{
    protected $fillable = [
        'district_id',
        'name',
        'number',
    ];

    public function district() {
        return $this->belongsTo(District::class);
    }
}
