<?php

namespace App\Transformers;

use App\Models\Building;
use League\Fractal\TransformerAbstract;

class BuildingTransformer extends TransformerAbstract
{
    /**
     * Transform a building.
     *
     * @param  Building $building
     *
     * @return array
     */
    public function transform(Building $building)
    {
        return [
            'id' => $building->id,
            'district_id' => $building->district_id,
            'name' => $building->name,
            'number' => $building->number,
            'count' => $building->flats->count(),
            'created_at' => $building->created_at->toIso8601String(),
            'updated_at' => $building->updated_at->toIso8601String(),
        ];
    }
}
