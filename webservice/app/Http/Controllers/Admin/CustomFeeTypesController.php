<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\CustomFeeType;
use App\Http\Requests\CustomFeeTypeRequest;
use App\Transformers\CustomFeeTypeTransformer;
use App\Http\Controllers\ApiController;


class CustomFeeTypesController extends ApiController
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
        
        $types = CustomFeeType::orderBy($sort, $order)->paginate($limit);

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($types, new CustomFeeTypeTransformer)
            );
        } else {
            $types = CustomFeeType::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($types, new CustomFeeTypeTransformer)
            );
        }
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(CustomFeeType::all(), new CustomFeeTypeTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomFeeTypeRequest $request)
    {
        if ($this->isAdmin()) {
        
            CustomFeeType::create($request->only('name'));

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
        $type = CustomFeeType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CustomFeeType not found');
        }

        return $this->response(
            $this->transform->item($type, new CustomFeeTypeTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomFeeTypeRequest $request, $id)
    {
        $type = CustomFeeType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CustomFeeType not found');
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
        $type = CustomFeeType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('CustomFeeType not found');
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
