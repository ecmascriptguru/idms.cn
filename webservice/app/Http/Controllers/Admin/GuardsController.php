<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Guard;
use App\Http\Requests\GuardRequest;
use App\Transformers\GuardTransformer;
use App\Http\Controllers\ApiController;


class GuardsController extends ApiController
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
    public function index(Request $request)
    {
        $sort = $this->getSort();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $parkingLotId = $request->get('pl');

        if ($parkingLotId || $parkingLotId > 0) {
            $guards = Guard::orderBy($sort, $order)->where(['parking_lot_id' => $parkingLotId])->paginate($limit);
        } else {
            $guards = Guard::orderBy($sort, $order)->paginate($limit);
        }

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($guards, new GuardTransformer)
            );
        } else {
            $guards = Guard::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($guards, new GuardTransformer)
            );
        }
    }

    public function fullList(Request $request)
    {
        $parkingLotId = $request->get('pl');

        if ($parkingLotId) {
            return $this->response(
                $this->transform->collection(Guard::where(['parking_lot_id' => $parkingLotId])->get(), new GuardTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(Guard::all(), new GuardTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuardRequest $request)
    {
        if ($this->isAdmin()) {
        
            Guard::create($request->only('parking_lot_id', 'name'));

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
        $guard = Guard::find($id);

        if (! $guard) {
            return $this->responseWithNotFound('Guard not found');
        }

        return $this->response(
            $this->transform->item($guard, new GuardTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GuardRequest $request, $id)
    {
        $guard = Guard::find($id);

        if (! $guard) {
            return $this->responseWithNotFound('Guard not found');
        }

        if ($this->isAdmin()) {
            $guard->parking_lot_id = $request->get('parking_lot_id');
            $guard->name = $request->get('name');
            $guard->save();

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
        $guard = Guard::find($id);

        if (! $guard) {
            return $this->responseWithNotFound('Guard not found');
        }

        if ($this->isAdmin()) 
        {
            $guard->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
