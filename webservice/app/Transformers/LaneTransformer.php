<?php

namespace App\Transformers;

use App\Models\Lane;
use League\Fractal\TransformerAbstract;

class LaneTransformer extends TransformerAbstract
{
    /**
     * Transform a lane.
     *
     * @param  Lane $lane
     *
     * @return array
     */
    public function transform(Lane $lane)
    {
        return [
            'id' => $lane->id,
            'parking_lot_id' => $lane->parking_lot_id,
            'guard_id' => $lane->guard_id,
            'name' => $lane->name,
            'number' => $lane->number,
            'control_number' => $lane->control_number,
            'parkingLot' => $lane->parkingLot,
            'guard' => $lane->parkingGuard(),
            'created_at' => $lane->created_at->toIso8601String(),
            'updated_at' => $lane->updated_at->toIso8601String(),
        ];
    }
}
