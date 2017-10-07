<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\CheckInPosition;
use App\Http\Requests\CheckInPositionRequest;
use App\Transformers\CheckInPositionTransformer;
use App\Http\Controllers\ApiController;


class CheckInPositionsController extends ApiController
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
        
        $types = CheckInPosition::orderBy($sort, $order)->paginate($limit);

        return $this->response(
            $this->transform->collection($types, new CheckInPositionTransformer)
        );
        
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(CheckInPosition::all(), new CheckInPositionTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckInPositionRequest $request)
    {
        if ($this->isAdmin()) {
        
            CheckInPosition::create($request->only('name'));

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
        $type = CheckInPosition::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CheckInPosition not found');
        }

        return $this->response(
            $this->transform->item($type, new CheckInPositionTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CheckInPositionRequest $request, $id)
    {
        $type = CheckInPosition::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CheckInPosition not found');
        }

        if ($this->isAdmin()) {
            $type->name = $request->get('name');
            $type->save();

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
        $type = CheckInPosition::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CheckInPosition not found');
        }

        if ($this->isAdmin()) 
        {
            $type->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
