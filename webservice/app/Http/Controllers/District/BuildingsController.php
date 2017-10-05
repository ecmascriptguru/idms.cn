<?php

namespace App\Http\Controllers\District;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Building;
use App\Http\Requests\BuildingRequest;
use App\Transformers\BuildingTransformer;
use App\Http\Controllers\ApiController;


class BuildingsController extends ApiController
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

        if ($districtId) {
            $roles = Building::orderBy($sort, $order)->where(['district_id' => $districtId])->paginate($limit);
        } elseif ($user && $user->district_id) {
            $roles = Building::orderBy($sort, $order)->where(['district_id' => $user->district_id])->paginate($limit);
        } else {
            $roles = Building::orderBy($sort, $order)->paginate($limit);
        }

        return $this->response(
            $this->transform->collection($roles, new BuildingTransformer)
        );
    }

    public function fullList(Request $request)
    {
        $districtId = $request->input('district_id');

        return $this->response(
            $this->transform->collection(Building::where(['district_id' => $districtId])->get(), new BuildingTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuildingRequest $request)
    {
        if ($this->isDistrictAdmin()) {
            $building = new Building;
            $building->district_id = Auth::guard('api')->user()->district_id;
            $building->name = $request->get('name');
            $building->number = $request->get('number');
            $building->save();

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
        $building = Building::find($id);

        if (! $building) {
            return $this->responseWithNotFound('Building not found');
        }

        return $this->response(
            $this->transform->item($building, new BuildingTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BuildingRequest $request, $id)
    {
        $building = Building::find($id);

        if (! $building) {
            return $this->responseWithNotFound('Building not found');
        }

        if ($this->isDistrictAdmin()) {
            $building->name = $request->get('name');
            $building->number = $request->get('number');
            $building->save();

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
        $building = Building::find($id);

        if (! $building) {
            return $this->responseWithNotFound('Building not found');
        }

        if ($this->isDistrictAdmin()) 
        {
            $building->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}