<?php

namespace App\Transformers;

use App\Models\Province;
use League\Fractal\TransformerAbstract;

class ProvinceTransformer extends TransformerAbstract
{
    /**
     * Transform a province.
     *
     * @param  Province $province
     *
     * @return array
     */
    public function transform(Province $province)
    {
        return [
            'id' => $province->id,
            'name' => $province->name,
            'created_at' => $province->created_at->toIso8601String(),
            'updated_at' => $province->updated_at->toIso8601String(),
        ];
    }
}
