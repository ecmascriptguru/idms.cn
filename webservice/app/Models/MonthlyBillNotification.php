<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\District;

class MonthlyBillNotification extends Model
{
    protected $fillable = [
        'district_id',
        'date',
        'is_released',
    ];

    public function district() {
        return $this->belongsTo(District::class);
    }
}
