<?php

namespace App\Transformers;

use App\Models\CustomFeeType;
use League\Fractal\TransformerAbstract;

class CustomFeeTypeTransformer extends TransformerAbstract
{
    /**
     * Transform a customFeeType.
     *
     * @param  CustomFeeType $customFeeType
     *
     * @return array
     */
    public function transform(CustomFeeType $customFeeType)
    {
        return [
            'id' => $customFeeType->id,
            'name' => $customFeeType->name,
            'created_at' => $customFeeType->created_at->toIso8601String(),
            'updated_at' => $customFeeType->updated_at->toIso8601String(),
        ];
    }
}
