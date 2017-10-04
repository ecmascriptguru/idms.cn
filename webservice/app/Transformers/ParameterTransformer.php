<?php

namespace App\Transformers;

use App\Models\Parameter;
use League\Fractal\TransformerAbstract;

class ParameterTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'operatingCompany',
    ];

    /**
     * List of default includes.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'operatingCompany',
    ];

    /**
     * Transform a parameter.
     *
     * @param  Product $parameter
     *
     * @return array
     */
    public function transform(Parameter $parameter)
    {
        return [
            'id' => $parameter->id,
            'video_intercom_call_waiting_time' => $parameter->video_intercom_call_waiting_time,
            'adv_refresh_waiting_time' => $parameter->adv_refresh_waiting_time,
            'device_connection_waiting_time' => $parameter->device_connection_waiting_time,
            'direct_phone_call_waiting_time_limit' => $parameter->direct_phone_call_waiting_time_limit,
            'password_input_waiting_time' => $parameter->password_input_waiting_time,
            'unit_number_length' => $parameter->unit_number_length,
            'floor_length_number' => $parameter->floor_length_number,
            'floor_length' => $parameter->floor_length,
            'media_sound_volumn' => $parameter->media_sound_volumn,
            'call_sound_volumn' => $parameter->call_sound_volumn,
            'dial_ring_tones' => $parameter->dial_ring_tones,
            'system_sound_volumn' => $parameter->system_sound_volumn,
            'video_quality' => $parameter->video_quality,
            'video_auto_adaption_network' => $parameter->video_auto_adaption_network,
        ];
    }

    /**
     * Include category.
     *
     * @param  Parameter $parameter
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeOperatingCompany(Parameter $parameter)
    {
        return $this->item($parameter->operatingCompany, new OperatingCompanyTransformer);
    }
}
