<?php

namespace App\Transformers;

use App\Models\HouseType;
use League\Fractal\TransformerAbstract;

class HouseTypeTransformer extends TransformerAbstract
{
    /**
     * Transform a houseType.
     *
     * @param  HouseType $houseType
     *
     * @return array
     */
    public function transform(HouseType $houseType)
    {
        return [
            'id' => $houseType->id,
            'name' => $houseType->name,
            'created_at' => $houseType->created_at->toIso8601String(),
            'updated_at' => $houseType->updated_at->toIso8601String(),
        ];
    }
}
