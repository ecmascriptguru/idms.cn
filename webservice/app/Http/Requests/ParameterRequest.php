<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParameterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video_intercom_call_waiting_time' => 'required',
            'adv_refresh_waiting_time' => 'required',
            'device_connection_waiting_time' => 'required',
            'direct_phone_call_waiting_time_limit' => 'required',
            'password_input_waiting_time' => 'required',
            'unit_number_length' => 'required',
            'floor_length_number' => 'required',
            'floor_length' => 'required',
            'media_sound_volumn' => 'required',
            'call_sound_volumn' => 'required',
            'dial_ring_tones' => 'required',
            'system_sound_volumn' => 'required',
            'video_quality' => 'required',
            'video_auto_adaption_network' => 'required',
        ];
    }
}
