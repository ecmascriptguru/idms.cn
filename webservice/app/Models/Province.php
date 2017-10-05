<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class Province extends Model
{
    protected $fillable = [
        'name',
    ];

    public function cities() {
        return $this->hasMany(City::class);
    }
}
