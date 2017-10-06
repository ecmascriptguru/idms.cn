<?php

namespace App\Http\Controllers\District;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\MonthlyBillNotification;
use App\Models\Bill;

use App\Http\Requests\MonthlyBillNotificationRequest;
use App\Transformers\MonthlyBillNotificationTransformer;
use App\Http\Controllers\ApiController;


class MonthlyBillNotificationsController extends ApiController
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
        $districtId = $request->input('district_id');

        if ($districtId) {
            $roles = MonthlyBillNotification::orderBy($sort, $order)->where(['district_id' => $districtId])->paginate($limit);
        } elseif ($user && $user->district_id) {
            $roles = MonthlyBillNotification::orderBy($sort, $order)->where(['district_id' => $user->district_id])->paginate($limit);
        } else {
            $roles = MonthlyBillNotification::orderBy($sort, $order)->paginate($limit);
        }

        return $this->response(
            $this->transform->collection($roles, new MonthlyBillNotificationTransformer)
        );
    }

    public function fullList(Request $request)
    {
        $user = Auth::guard('api')->user();
        $districtId = $request->get('dct_id');

        if ($districtId) {
            return $this->response(
                $this->transform->collection(MonthlyBillNotification::where(['district_id' => $districtId])->get(), new MonthlyBillNotificationTransformer)
            );
        } elseif ($user && $user->district_id) {
            return $this->response(
                $this->transform->collection(MonthlyBillNotification::where(['district_id' => $user->district_id])->get(), new MonthlyBillNotificationTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(MonthlyBillNotification::where(['district_id' => null])->get(), new MonthlyBillNotificationTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MonthlyBillNotificationRequest $request)
    {
        if ($this->isDistrictAdmin()) {
            $districtId = Auth::guard('api')->user()->district_id;
            $notifications = MonthlyBillNotification::where(['district_id' => $districtId, 'date' => $request->get('date')])->get();

            if ($notifications->count() > 0) {
                return $this->response(['result' => 'failure']);
            }

            $notification = new MonthlyBillNotification;
            $notification->district_id = Auth::guard('api')->user()->district_id;
            $notification->date = $request->get('date');
            $notification->save();

            $district = $notification->district;
            $flats = $district->flats;

            foreach ($flats as $flat) {
                $building = $flat->building;
                $feeStandard = $flat->feeStandard();
                $bill = new Bill;
                $bill->district_id = $district->id;
                $bill->building_id = $building->id;
                $bill->flat_id = $flat->id;
                $bill->monthly_bill_notification_id = $notification->id;
                $bill->area = $flat->area;
                $bill->total = $feeStandard->property_management_fee * $flat->area;

                $bill->save();
            }

            return $this->response(['result' => 'success', 'flats' => $flats]);
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
        $notification = MonthlyBillNotification::find($id);

        if (! $notification) {
            return $this->responseWithNotFound('MonthlyBillNotification not found');
        }

        return $this->response(
            $this->transform->item($notification, new MonthlyBillNotificationTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MonthlyBillNotificationRequest $request, $id)
    {
        // $notification = MonthlyBillNotification::find($id);

        // if (! $notification) {
        //     return $this->responseWithNotFound('MonthlyBillNotification not found');
        // }

        // if ($this->isDistrictAdmin()) {
        //     $notification->is_released = $request->get('is_released');
        //     $notification->save();

        //     return $this->response(['result' => 'success']);
        // }
        // else 
        // {
        //     return $this->response(['result' => 'failure']);
        // }
        return $this->response(['result' => 'failure']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $notification = MonthlyBillNotification::find($id);

        // if (! $notification) {
        //     return $this->responseWithNotFound('MonthlyBillNotification not found');
        // }

        // if ($this->isDistrictAdmin()) 
        // {
        //     $notification->delete();
            
        //     return $this->response(['result' => 'success']);
        // }
        // else{
        //     return $this->response(['result' => 'failure']);
        // }
        return $this->response(['result' => 'failure']);
    }


    public function release(Request $request) {
        $notification = MonthlyBillNotification::find($request->get('id'));

        if ($notification) {
            $notification->is_released = true;
            $notification->save();

            return $this->response(['result' => 'success']);
        } else {
            return $this->responseWithNotFound('MonthlyBillNotification not found');
        }
    }
}