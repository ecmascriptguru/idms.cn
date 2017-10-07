<?php

namespace App\Transformers;

use App\Models\CheckInDevice;
use League\Fractal\TransformerAbstract;

class CheckInDeviceTransformer extends TransformerAbstract
{
    /**
     * Transform a device.
     *
     * @param  CheckInDevice $device
     *
     * @return array
     */
    public function transform(CheckInDevice $device)
    {
        return [
            'id' => $device->id,
            'check_in_type_id' => $device->check_in_type_id,
            'check_in_position_id' => $device->check_in_position_id,
            'district_id' => $device->district_id,
            'building_id' => $device->building_id,
            'name' => $device->name,
            'mac_address' => $device->mac_address,
            'is_connected_to_elevator' => $device->is_connected_to_elevator,
            'status' => $device->status,

            'checkInType' => $device->checkInType,
            'checkInPosition' => $device->checkInPosition,
            'district' => $device->district,
            'building' => $device->building,
            'created_at' => $device->created_at->toIso8601String(),
            'updated_at' => $device->updated_at->toIso8601String(),
        ];
    }
}
