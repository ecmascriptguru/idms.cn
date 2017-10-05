<?php

namespace App\Transformers;

use App\Models\Flat;
use League\Fractal\TransformerAbstract;

class FlatTransformer extends TransformerAbstract
{
    /**
     * Transform a flat.
     *
     * @param  Flat $flat
     *
     * @return array
     */
    public function transform(Flat $flat)
    {
        return [
            'id' => $flat->id,
            'district_id' => $flat->district_id,
            'district' => $flat->district,
            'building_id' => $flat->building_id,
            'building' => $flag->building,
            'house_type_id' => $flat->house_type_id,
            'houseType' => $flag->houseType,
            'name' => $flat->name,
            'number' => $flat->number,
            'area' => $flat->area,
            'owner_1_name' => $flat->owner_1_name,
            'owner_1_document_type' => $flat->owner_1_document_type,
            'owner_1_document_number' => $flat->owner_1_document_number,
            'owner_1_phone' => $flat->owner_1_phone,
            'owner_2_name' => $flat->owner_2_name,
            'owner_2_document_type' => $flat->owner_2_document_type,
            'owner_2_document_number' => $flat->owner_2_document_number,
            'owner_2_phone' => $flat->owner_2_phone,
            'created_at' => $flat->created_at->toIso8601String(),
            'updated_at' => $flat->updated_at->toIso8601String(),
        ];
    }
}
