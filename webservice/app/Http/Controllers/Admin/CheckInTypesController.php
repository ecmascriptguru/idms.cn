<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\CheckInType;
use App\Http\Requests\CheckInTypeRequest;
use App\Transformers\CheckInTypeTransformer;
use App\Http\Controllers\ApiController;


class CheckInTypesController extends ApiController
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
        
        $types = CheckInType::orderBy($sort, $order)->paginate($limit);

        return $this->response(
            $this->transform->collection($types, new CheckInTypeTransformer)
        );
        
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(CheckInType::all(), new CheckInTypeTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckInTypeRequest $request)
    {
        if ($this->isAdmin()) {
        
            CheckInType::create($request->only('name'));

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
        $type = CheckInType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CheckInType not found');
        }

        return $this->response(
            $this->transform->item($type, new CheckInTypeTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CheckInTypeRequest $request, $id)
    {
        $type = CheckInType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CheckInType not found');
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
        $type = CheckInType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CheckInType not found');
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
