<?php

namespace App\Http\Controllers\District;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\CheckInDevice;
use App\Http\Requests\CheckInDeviceRequest;
use App\Transformers\CheckInDeviceTransformer;
use App\Http\Controllers\ApiController;


class CheckInDevicesController extends ApiController
{
    public function isDistrictAdmin() {
        $user = Auth::guard('api')->user();

        return ($user && $user->role) ? $user->role->id === 4 : false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $this->getSort();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        
        $user = Auth::guard('api')->user();
        $buildingId = $request->input('building_id');

        if ($buildingId) {
            $devices = CheckInDevice::orderBy($sort, $order)->where(['building_id' => $buildingId])->paginate($limit);
        } elseif ($user && $user->district_id) {
            $devices = CheckInDevice::orderBy($sort, $order)->where(['district_id' => $user->district_id])->paginate($limit);
        } else {
            $devices = CheckInDevice::orderBy($sort, $order)->paginate($limit);
        }

        return $this->response(
            $this->transform->collection($devices, new CheckInDeviceTransformer)
        );
    }

    public function fullList(Request $request)
    {
        $buildingId = $request->input('building_id');
        $user = Auth::guard('api')->user();

        if ($user && $buildingId) {
            return $this->response(
                $this->transform->collection(CheckInDevice::where(['building_id' => $buildingId])->get(), new CheckInDeviceTransformer)
            );
        } elseif ($user && $user->district_id) {
            return $this->response(
                $this->transform->collection(CheckInDevice::where(['district_id' => $user->district_id])->get(), new CheckInDeviceTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(CheckInDevice::where(['district_id' => null])->get(), new CheckInDeviceTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckInDeviceRequest $request)
    {
        if ($this->isDistrictAdmin()) {
            $districtId = Auth::guard('api')->user()->district_id;
            $params = $request->only(
                'check_in_type_id',
                'check_in_position_id',
                'building_id',
                'name',
                'mac_address',
                'is_connected_to_elevator'
            );
            $params['district_id'] = $districtId;

            $device = CheckInDevice::create($params);
            // $device->district_id = Auth::guard('api')->user()->district_id;
            // $device->save();

            return $this->response(['result' => 'success']);
        } else {
            return $this->response(['result' => 'failure']);
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
        $device = CheckInDevice::find($id);

        if (! $device) {
            return $this->responseWithNotFound('CheckInDevice not found');
        }

        return $this->response(
            $this->transform->item($device, new CheckInDeviceTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CheckInDeviceRequest $request, $id)
    {
        $device = CheckInDevice::find($id);

        if (! $device) {
            return $this->responseWithNotFound('CheckInDevice not found');
        }

        if ($this->isDistrictAdmin()) {
            $device->building_id = $request->get('building_id');
            $device->check_in_type_id = $request->get('check_in_type_id');
            $device->check_in_position_id = $request->get('check_in_position_id');
            $device->name = $request->get('name');
            $device->mac_address = $request->get('mac_address');
            $device->is_connected_to_elevator = $request->get('is_connected_to_elevator');
            $device->save();

            return $this->response(['result' => 'success']);
        }
        else 
        {
            return $this->response(['result' => 'failure']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $device = CheckInDevice::find($id);

        if (! $device) {
            return $this->responseWithNotFound('CheckInDevice not found');
        }

        if ($this->isDistrictAdmin())
        {
            $device->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }

    public function reboot($id) {
        return $this->response(['result' => 'success']);
    }
}