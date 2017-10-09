<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Lane;
use App\Http\Requests\LaneRequest;
use App\Transformers\LaneTransformer;
use App\Http\Controllers\ApiController;


class LanesController extends ApiController
{
    public function isAdmin() {
        $user = Auth::lane('api')->user();

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
        $guardId = $request->get('guard');

        if ($guardId || $guardId > 0) {
            $lanes = Lane::orderBy($sort, $order)->where(['guard_id' => $guardId])->paginate($limit);
        } else {
            $lanes = Lane::orderBy($sort, $order)->paginate($limit);
        }

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($lanes, new LaneTransformer)
            );
        } else {
            $lanes = Lane::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($lanes, new LaneTransformer)
            );
        }
    }

    public function fullList(Request $request)
    {
        $guardId = $request->get('guard');

        if ($guardId) {
            return $this->response(
                $this->transform->collection(Lane::where(['guard_id' => $guardId])->get(), new LaneTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(Lane::all(), new LaneTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LaneRequest $request)
    {
        if ($this->isAdmin()) {
        
            Lane::create($request->only('parking_lot_id', 'guard_id', 'name'));

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
        $lane = Lane::find($id);

        if (! $lane) {
            return $this->responseWithNotFound('Lane not found');
        }

        return $this->response(
            $this->transform->item($lane, new LaneTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LaneRequest $request, $id)
    {
        $lane = Lane::find($id);

        if (! $lane) {
            return $this->responseWithNotFound('Lane not found');
        }

        if ($this->isAdmin()) {
            $lane->parking_lot_id = $request->get('parking_lot_id');
            $lane->guard_id = $request->get('guard_id');
            $lane->name = $request->get('name');
            $lane->save();

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
        $lane = Lane::find($id);

        if (! $lane) {
            return $this->responseWithNotFound('Lane not found');
        }

        if ($this->isAdmin()) 
        {
            $lane->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
