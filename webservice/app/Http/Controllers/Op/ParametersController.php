<?php

namespace App\Http\Controllers\Op;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OperatingCompany;
use App\Models\Parameter;
use App\Http\Requests\ParameterRequest;
use App\Transformers\ParameterTransformer;
use App\Http\Controllers\ApiController;

class ParametersController extends ApiController
{
    public function isOperatingCompanyAdmin() {
        $user = Auth::guard('api')->user();

        return ($user && $user->role) ? $user->role->id === 2 : false;
    }

    private function getOperatingCompanyId() {
        $user = Auth::guard('api')->user();
        
        if ($user && $user->organization) {
            return $user->organization->id;
        } else {
            return null;
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $operatingCompanyId = $this->getOperatingCompanyId();
        if (!$operatingCompanyId) {
            return ['result' => false];
        }
        
        $parameters = Parameter::where(['operating_company_id' => $operatingCompanyId])->get();

        if ($parameters->count() == 0) {
            $parameter = Parameter::create(['operating_company_id' => $operatingCompanyId]);
            $parameter->save();
        } else {
            $parameter = $parameters[0];
        }

        return $this->response(
            $this->transform->item($parameter, new ParameterTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ParameterRequest $request, $id)
    {
        $operatingCompanyId = $this->getOperatingCompanyId();
        if (!$operatingCompanyId) {
            return ['result' => false];
        }
        
        $parameters = Parameter::where(['operating_company_id' => $operatingCompanyId])->get();
        $parameter = $parameters[0];

        if (! $parameter) {
            return $this->responseWithNotFound('Parameter not found');
        }

        if ($this->isOperatingCompanyAdmin()) {
            $parameter->video_intercom_call_waiting_time = $request->get('video_intercom_call_waiting_time');
            $parameter->adv_refresh_waiting_time = $request->get('adv_refresh_waiting_time');
            $parameter->device_connection_waiting_time = $request->get('device_connection_waiting_time');
            $parameter->direct_phone_call_waiting_time_limit = $request->get('direct_phone_call_waiting_time_limit');
            $parameter->password_input_waiting_time = $request->get('password_input_waiting_time');
            $parameter->unit_number_length = $request->get('unit_number_length');
            $parameter->floor_length_number = $request->get('floor_length_number');
            $parameter->floor_length = $request->get('floor_length');
            $parameter->media_sound_volumn = $request->get('media_sound_volumn');
            $parameter->call_sound_volumn = $request->get('call_sound_volumn');
            $parameter->dial_ring_tones = $request->get('dial_ring_tones');
            $parameter->system_sound_volumn = $request->get('system_sound_volumn');
            $parameter->video_quality = $request->get('video_quality');
            $parameter->video_auto_adaption_network = $request->get('video_auto_adaption_network');
            $parameter->save();
            return $this->response(['result' => 'success']);
        } else {
            return $this->response(['result' => 'failure']);
        }
    }
}
