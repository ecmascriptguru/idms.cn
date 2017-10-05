<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeStandardRequest extends FormRequest
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
            'district_id' => 'required',
            'house_type_id' => 'required',
            'property_management_fee' => 'required',
            'water_fee' => 'required',
            'electricity_fee' => 'required',
            'parking_fee' => 'required',
            'gas_fee' => 'required',
            'heating_fee' => 'required',
            'internet_fee' => 'required',
            'custom_fee_1_type_id' => 'required',
            'custom_fee_1_name' => 'required',
            'custom_fee_1_rate' => 'required',
            'custom_fee_2_type_id' => 'required',
            'custom_fee_2_name' => 'required',
            'custom_fee_2_rate' => 'required',
            'custom_fee_3_type_id' => 'required',
            'custom_fee_3_name' => 'required',
            'custom_fee_3_rate' => 'required',
        ];
    }
}
