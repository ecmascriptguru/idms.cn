<?php

namespace App\Transformers;

use App\Models\ParkingLot;
use League\Fractal\TransformerAbstract;

class ParkingLotTransformer extends TransformerAbstract
{
    /**
     * Transform a parkingLot.
     *
     * @param  ParkingLot $parkingLot
     *
     * @return array
     */
    public function transform(ParkingLot $parkingLot)
    {
        return [
            'id' => $parkingLot->id,
            'name' => $parkingLot->name,
            'address' => $parkingLot->address,
            'contact' => $parkingLot->contact,
            'phone' => $parkingLot->phone,
            'guards' => $parkingLot->guards,
            'created_at' => $parkingLot->created_at->toIso8601String(),
            'updated_at' => $parkingLot->updated_at->toIso8601String(),
        ];
    }
}
