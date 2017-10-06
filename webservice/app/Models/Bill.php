<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\District;
use App\Models\Building;
use App\Models\Flat;
use App\Models\MonthlyBillNotification;
use App\Models\FeeStandard;

class Bill extends Model
{
    protected $fillable = [
        'district_id',
        'building_id',
        'flat_id',
        'monthly_bill_notification_id',
        'area',
        'water',
        'electricity',
        'cars',
        'gas',
        'heating',
        'internet',
        'total',
        'is_paid',

    ];

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function building() {
        return $this->belongsTo(Building::class);
    }

    public function flat() {
        return $this->belongsTo(Flat::class);
    }

    public function monthlyBillNotification() {
        return $this->belongsTo(MonthlyBillNotification::class);
    }

    public function feeStandard() {
        $houseType = $this->flat->houseType;
        return $houseType->feeStandard;
    }
}
