<?php

namespace App\Transformers;

use App\Models\City;
use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract
{
    /**
     * Transform a city.
     *
     * @param  City $city
     *
     * @return array
     */
    public function transform(City $city)
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
            'created_at' => $city->created_at->toIso8601String(),
            'updated_at' => $city->updated_at->toIso8601String(),
        ];
    }
}
