<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\Province;
use App\Http\Requests\ProvinceRequest;
use App\Transformers\ProvinceTransformer;
use App\Http\Controllers\ApiController;


class ProvincesController extends ApiController
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
        
        $provinces = Province::orderBy($sort, $order)->paginate($limit);

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($provinces, new ProvinceTransformer)
            );
        } else {
            $provinces = Province::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($provinces, new ProvinceTransformer)
            );
        }
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(Province::all(), new ProvinceTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProvinceRequest $request)
    {
        if ($this->isAdmin()) {
        
            Province::create($request->only('name'));

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
        $province = Province::find($id);

        if (! $province) {
            return $this->responseWithNotFound('Province not found');
        }

        return $this->response(
            $this->transform->item($province, new ProvinceTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProvinceRequest $request, $id)
    {
        $province = Province::find($id);

        if (! $province) {
            return $this->responseWithNotFound('Province not found');
        }

        if ($this->isAdmin()) {
            $province->name = $request->get('name');
            $province->save();

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
        $province = Province::find($id);

        if (! $province) {
            return $this->responseWithNotFound('Province not found');
        }

        if ($this->isAdmin()) 
        {
            $province->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
