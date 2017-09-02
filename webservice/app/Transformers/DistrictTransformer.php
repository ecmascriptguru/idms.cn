<?php

namespace App\Transformers;

use App\Models\District;
use League\Fractal\TransformerAbstract;

class DistrictTransformer extends TransformerAbstract
{
    /**
     * Transform a operating company.
     *
     * @param  District $district
     *
     * @return array
     */
    public function transform(District $district)
    {
        return [
            'id' => $district->id,
            'name' => $district->name,
            'short_name' => $district->short_name,
            'phone' => $district->phone,
            'contact' => $district->contact,
            'address' => $district->address,
            'operatingCompany' => $district->operatingCompany,
            'propertyCompany' => $district->propertyCompany,
            'count' => count($district->users()),
            'created_at' => $district->created_at->toIso8601String(),
            'updated_at' => $district->updated_at->toIso8601String(),
        ];
    }
}
