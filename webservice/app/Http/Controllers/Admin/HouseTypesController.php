<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\HouseType;
use App\Http\Requests\HouseTypeRequest;
use App\Transformers\HouseTypeTransformer;
use App\Http\Controllers\ApiController;


class HouseTypesController extends ApiController
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
        
        $types = HouseType::orderBy($sort, $order)->paginate($limit);

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($types, new HouseTypeTransformer)
            );
        } else {
            $types = HouseType::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($types, new HouseTypeTransformer)
            );
        }
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(HouseType::all(), new HouseTypeTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseTypeRequest $request)
    {
        if ($this->isAdmin()) {
        
            HouseType::create($request->only('name'));

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
        $type = HouseType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('HouseType not found');
        }

        return $this->response(
            $this->transform->item($type, new HouseTypeTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HouseTypeRequest $request, $id)
    {
        $type = HouseType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('HouseType not found');
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
        $type = HouseType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('HouseType not found');
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
