<?php

namespace App\Transformers;

use App\Models\CheckInType;
use League\Fractal\TransformerAbstract;

class CheckInTypeTransformer extends TransformerAbstract
{
    /**
     * Transform a type.
     *
     * @param  CheckInType $type
     *
     * @return array
     */
    public function transform(CheckInType $type)
    {
        return [
            'id' => $type->id,
            'name' => $type->name,
            'created_at' => $type->created_at->toIso8601String(),
            'updated_at' => $type->updated_at->toIso8601String(),
        ];
    }
}
