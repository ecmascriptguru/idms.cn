<?php

namespace App\Transformers;

use App\Models\FeeStandard;
use League\Fractal\TransformerAbstract;

class FeeStandardTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
     protected $availableIncludes = [
        'district',
        'houseType',
    ];

    /**
     * List of default includes.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'district',
        'houseType',
    ];

    /**
     * Transform a feeStandard.
     *
     * @param  FeeStandard $feeStandard
     *
     * @return array
     */
    public function transform(FeeStandard $feeStandard)
    {
        return [
            'id' => $feeStandard->id,
            'district_id' => $feeStandard->district_id,
            'house_type_id' => $feeStandard->house_type_id,
            'property_management_fee' => $feeStandard->property_management_fee,
            'water_fee' => $feeStandard->water_fee,
            'electricity_fee' => $feeStandard->electricity_fee,
            'parking_fee' => $feeStandard->parking_fee,
            'gas_fee' => $feeStandard->gas_fee,
            'heating_fee' => $feeStandard->heating_fee,
            'internet_fee' => $feeStandard->internet_fee,
            'custom_fee_1_type_id' => $feeStandard->custom_fee_1_type_id,
            'custom_fee_1_name' => $feeStandard->custom_fee_1_name,
            'custom_fee_1_rate' => $feeStandard->custom_fee_1_rate,
            'custom_fee_2_type_id' => $feeStandard->custom_fee_2_type_id,
            'custom_fee_2_name' => $feeStandard->custom_fee_2_name,
            'custom_fee_2_rate' => $feeStandard->custom_fee_2_rate,
            'custom_fee_3_type_id' => $feeStandard->custom_fee_3_type_id,
            'custom_fee_3_name' => $feeStandard->custom_fee_3_name,
            'custom_fee_3_rate' => $feeStandard->custom_fee_3_rate,
            'created_at' => $feeStandard->created_at->toIso8601String(),
            'updated_at' => $feeStandard->updated_at->toIso8601String(),
        ];
    }

    /**
     * Include district.
     *
     * @param  FeeStandard $feeStandard
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeDistrict(FeeStandard $feeStandard)
    {
        return $this->item($feeStandard->district, new DistrictTransformer);
    }

    /**
     * Include houseType.
     *
     * @param  FeeStandard $feeStandard
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeHouseType(FeeStandard $feeStandard)
    {
        return $this->item($feeStandard->houseType, new HouseTypeTransformer);
    }
}
