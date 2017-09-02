<?php

namespace App\Transformers;

use App\Models\OperatingCompany;
use League\Fractal\TransformerAbstract;

class OperatingCompanyTransformer extends TransformerAbstract
{
    /**
     * Transform a operating company.
     *
     * @param  OperatingCompany $oc
     *
     * @return array
     */
    public function transform(OperatingCompany $oc)
    {
        return [
            'id' => $oc->id,
            'name' => $oc->name,
            'short_name' => $oc->short_name,
            'phone' => $oc->phone,
            'contact' => $oc->contact,
            'address' => $oc->address,
            'count' => count($oc->districts),
            'shop' => $oc->shop,
            // 'count' => count($oc->users()),
            'created_at' => $oc->created_at->toIso8601String(),
            'updated_at' => $oc->updated_at->toIso8601String(),
        ];
    }
}
