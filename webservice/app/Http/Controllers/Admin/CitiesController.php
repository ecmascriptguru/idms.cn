<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\City;
use App\Http\Requests\CityRequest;
use App\Transformers\CityTransformer;
use App\Http\Controllers\ApiController;


class CitiesController extends ApiController
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
        $provinceId = $request->input('province_id');

        if ($provinceId) {
            $cities = City::orderBy($sort, $order)->where(['province_id' => $provinceId])->paginate($limit);
        } else {
            $cities = City::orderBy($sort, $order)->paginate($limit);
        }

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($cities, new CityTransformer)
            );
        } else {
            $cities = City::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($cities, new CityTransformer)
            );
        }
    }

    public function fullList(Request $request)
    {
        $provinceId = $request->input('province_id');

        if ($provinceId) {
            return $this->response(
                $this->transform->collection(City::where(['province_id' => $provinceId])->get(), new CityTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(City::all(), new CityTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        if ($this->isAdmin()) {
        
            City::create($request->only('province_id', 'name'));

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
        $city = City::find($id);

        if (! $city) {
            return $this->responseWithNotFound('City not found');
        }

        return $this->response(
            $this->transform->item($city, new CityTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, $id)
    {
        $city = City::find($id);

        if (! $city) {
            return $this->responseWithNotFound('City not found');
        }

        if ($this->isAdmin()) {
            $city->province_id = $request->get('province_id');
            $city->name = $request->get('name');
            $city->save();

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
        $city = City::find($id);

        if (! $city) {
            return $this->responseWithNotFound('City not found');
        }

        if ($this->isAdmin()) 
        {
            $city->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
