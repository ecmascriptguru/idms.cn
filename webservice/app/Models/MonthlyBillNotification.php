<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\District;
use App\Models\Bill;

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

    public function bills() {
        return $this->hasMany(Bill::class);
    }
}
