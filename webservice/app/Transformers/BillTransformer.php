<?php

namespace App\Transformers;

use App\Models\Bill;
use League\Fractal\TransformerAbstract;

class BillTransformer extends TransformerAbstract
{
    /**
     * Transform a bill.
     *
     * @param  Bill $bill
     *
     * @return array
     */
    public function transform(Bill $bill)
    {
        return [
            'id' => $bill->id,
            'district_id' => $bill->district_id,
            'building_id' => $bill->building_id,
            'flat_id' => $bill->flat_id,
            'monthly_bill_notification_id' => $bill->monthly_bill_notification_id,
            'area' => $bill->area,
            'water' => $bill->water,
            'electricity' => $bill->electricity,
            'cars' => $bill->cars,
            'gas' => $bill->gas,
            'heating' => $bill->heating,
            'internet' => $bill->internet,
            'total' => $bill->total,
            'is_paid' => $bill->is_paid,
            'created_at' => $bill->created_at->toIso8601String(),
            'updated_at' => $bill->updated_at->toIso8601String(),

            'date' => $bill->monthlyBillNotification->date,
            'district' => $bill->district,
            'building' => $bill->building,
            'flat' => $bill->flat,
            'feeStandard' => $bill->feeStandard(),
        ];
    }
}
