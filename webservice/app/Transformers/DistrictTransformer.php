<?php

namespace App\Transformers;

use App\Models\District;
use League\Fractal\TransformerAbstract;

class DistrictTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    //  protected $availableIncludes = [
    //     'parkingLot',
    // ];

    /**
     * List of default includes.
     *
     * @var array
     */
    // protected $defaultIncludes = [
    //     'parkingLot',
    // ];

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
            'province' => $district->province,
            'city' => $district->city,
            'phone' => $district->phone,
            'contact' => $district->contact,
            'address' => $district->address,
            'operatingCompany' => $district->operatingCompany,
            'propertyCompany' => $district->propertyCompany,
            'count' => count($district->users()),
            'parking_lot_id' => $district->parking_lot_id,
            'parkingLot' => $district->parkingLot,
            'created_at' => $district->created_at->toIso8601String(),
            'updated_at' => $district->updated_at->toIso8601String(),
        ];
    }

    /**
     * Include parkingLot.
     *
     * @param  District $district
     *
     * @return \League\Fractal\Resource\Item
     */
    // public function includeParkingLot(District $district)
    // {
    //     return $this->item($district->parkingLot, new ParkingLotTransformer);
    // }
}
