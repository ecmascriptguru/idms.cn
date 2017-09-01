<?php

namespace App\Transformers;

use App\Models\PropertyCompany;
use League\Fractal\TransformerAbstract;

class PropertyCompanyTransformer extends TransformerAbstract
{
    /**
     * Transform a operating company.
     *
     * @param  PropertyCompany $pc
     *
     * @return array
     */
    public function transform(PropertyCompany $pc)
    {
        return [
            'id' => $pc->id,
            'name' => $pc->name,
            'short_name' => $pc->short_name,
            'phone' => $pc->phone,
            'contact' => $pc->contact,
            'address' => $pc->address,
            'count' => count($pc->users()),
            'created_at' => $pc->created_at->toIso8601String(),
            'updated_at' => $pc->updated_at->toIso8601String(),
        ];
    }
}
