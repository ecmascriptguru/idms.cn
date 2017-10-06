<?php

namespace App\Http\Controllers\District;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Bill;

use App\Http\Requests\BillRequest;
use App\Transformers\BillTransformer;
use App\Http\Controllers\ApiController;


class BillsController extends ApiController
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
        $buildingId = $request->get('building_id');
        $date = $request->get('date');

        $query = Bill::orderBy($sort, $order);

        if ($user && $user->district_id) {
            $query = $query->where(['district_id' => $user->district_id]);
        }

        if ($districtId) {
            $query = $query->where(['district_id' => $districtId]);
        }

        if ($buildingId) {
            $query = $query->where(['building_id' => $buildingId]);
        }

        if ($date) {
            $query = $query->where(['date' => $date]);
        }

        $bills = $query->paginate($limit);

        return $this->response(
            $this->transform->collection($bills, new BillTransformer)
        );
    }

    public function fullList(Request $request)
    {
        $user = Auth::guard('api')->user();
        $districtId = $request->get('dct_id');

        if ($districtId) {
            return $this->response(
                $this->transform->collection(Bill::where(['district_id' => $districtId])->get(), new BillTransformer)
            );
        } elseif ($user && $user->district_id) {
            return $this->response(
                $this->transform->collection(Bill::where(['district_id' => $user->district_id])->get(), new BillTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(Bill::where(['district_id' => null])->get(), new BillTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillRequest $request)
    {
        // if ($this->isDistrictAdmin()) {
        //     $districtId = Auth::guard('api')->user()->district_id;
        //     $notifications = Bill::where(['district_id' => $districtId, 'date' => $request->get('date')])->get();

        //     if ($notifications->count() > 0) {
        //         return $this->response(['result' => 'failure']);
        //     }

        //     $notification = new Bill;
        //     $notification->district_id = Auth::guard('api')->user()->district_id;
        //     $notification->date = $request->get('date');
        //     $notification->save();

        //     $district = $notification->district;
        //     $flats = $district->flats;

        //     foreach ($flats as $flat) {
        //         $building = $flat->building;
        //         $feeStandard = $flat->feeStandard();
        //         $bill = new Bill;
        //         $bill->district_id = $district->id;
        //         $bill->building_id = $building->id;
        //         $bill->flat_id = $flat->id;
        //         $bill->monthly_bill_notification_id = $notification->id;
        //         $bill->area = $flat->area;
        //         $bill->total = $feeStandard->property_management_fee * $flat->area;

        //         $bill->save();
        //     }

        //     return $this->response(['result' => 'success', 'flats' => $flats]);
        // } else {
        //     return $this->response(['result' => 'failure']);
        // }
        return $this->response(['result' => 'failure']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = Bill::find($id);

        if (! $bill) {
            return $this->responseWithNotFound('Bill not found');
        }

        return $this->response(
            $this->transform->item($bill, new BillTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BillRequest $request, $id)
    {
        $bill = Bill::find($id);

        if (! $bill) {
            return $this->responseWithNotFound('Bill not found');
        }

        if ($this->isDistrictAdmin()) {
            $bill->water = $request->get('water');
            $bill->electricity = $request->get('electricity');
            $bill->total = $request->get('total');
            $bill->save();

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
        // $notification = Bill::find($id);

        // if (! $notification) {
        //     return $this->responseWithNotFound('Bill not found');
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


    public function pay(Request $request) {
        $bill = Bill::find($request->get('id'));

        if ($bill) {
            $bill->is_paid = true;
            $bill->save();

            return $this->response(['result' => 'success']);
        } else {
            return $this->responseWithNotFound('Bill not found');
        }
    }
}