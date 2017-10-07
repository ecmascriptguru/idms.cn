<?php

namespace App\Transformers;

use App\Models\Guard;
use League\Fractal\TransformerAbstract;

class GuardTransformer extends TransformerAbstract
{
    /**
     * Transform a guard.
     *
     * @param  Guard $guard
     *
     * @return array
     */
    public function transform(Guard $guard)
    {
        return [
            'id' => $guard->id,
            'parking_lot_id' => $guard->parking_lot_id,
            'name' => $guard->name,
            'parkingLot' => $guard->parkingLot,
            'created_at' => $guard->created_at->toIso8601String(),
            'updated_at' => $guard->updated_at->toIso8601String(),
        ];
    }
}
