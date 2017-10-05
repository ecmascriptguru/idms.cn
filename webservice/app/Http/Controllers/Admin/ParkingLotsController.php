<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\ParkingLot;
use App\Http\Requests\ParkingLotRequest;
use App\Transformers\ParkingLotTransformer;
use App\Http\Controllers\ApiController;


class ParkingLotsController extends ApiController
{
    public function isAdmin() {
        $user = Auth::guard('api')->user();

        return ($user && $user->role) ? $user->role->id === 1 : false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = $this->getSort();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        
        $parkingLots = ParkingLot::orderBy($sort, $order)->paginate($limit);

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($parkingLots, new ParkingLotTransformer)
            );
        } else {
            $parkingLots = ParkingLot::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($parkingLots, new ParkingLotTransformer)
            );
        }
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(ParkingLot::all(), new ParkingLotTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ParkingLotRequest $request)
    {
        if ($this->isAdmin()) {
        
            ParkingLot::create($request->only('name', 'address', 'contact', 'phone'));

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
        $parkingLot = ParkingLot::find($id);

        if (! $parkingLot) {
            return $this->responseWithNotFound('ParkingLot not found');
        }

        return $this->response(
            $this->transform->item($parkingLot, new ParkingLotTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ParkingLotRequest $request, $id)
    {
        $parkingLot = ParkingLot::find($id);

        if (! $parkingLot) {
            return $this->responseWithNotFound('ParkingLot not found');
        }

        if ($this->isAdmin()) {
            $parkingLot->name = $request->get('name');
            $parkingLot->address = $request->get('address');
            $parkingLot->contact = $request->get('contact');
            $parkingLot->phone = $request->get('phone');
            $parkingLot->save();

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
        $parkingLot = ParkingLot::find($id);

        if (! $parkingLot) {
            return $this->responseWithNotFound('ParkingLot not found');
        }

        if ($this->isAdmin()) 
        {
            $parkingLot->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
