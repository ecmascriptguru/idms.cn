<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaneRequest extends FormRequest
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
            'parking_lot_id' => 'required',
            'guard_id' => 'required',
            'name' => 'required',
            'number' => 'required',
        ];
    }
}
