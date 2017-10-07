<?php

namespace App\Transformers;

use App\Models\CheckInPosition;
use League\Fractal\TransformerAbstract;

class CheckInPositionTransformer extends TransformerAbstract
{
    /**
     * Transform a position.
     *
     * @param  CheckInPosition $position
     *
     * @return array
     */
    public function transform(CheckInPosition $position)
    {
        return [
            'id' => $position->id,
            'name' => $position->name,
            'created_at' => $position->created_at->toIso8601String(),
            'updated_at' => $position->updated_at->toIso8601String(),
        ];
    }
}
