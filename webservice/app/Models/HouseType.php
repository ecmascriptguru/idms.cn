<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FeeStandard;

class HouseType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function feeStandards() {
        return $this->hasMany(FeeStandard::class);
    }
}
