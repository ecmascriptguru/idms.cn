<?php

namespace App\Http\Controllers\District;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Flat;
use App\Http\Requests\FlatRequest;
use App\Transformers\FlatTransformer;
use App\Http\Controllers\ApiController;


class FlatsController extends ApiController
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
        $buildingId = $request->input('building_id');

        if ($buildingId) {
            $flats = Flat::orderBy($sort, $order)->where(['building_id' => $buildingId])->paginate($limit);
        } elseif ($user && $user->district_id) {
            $flats = Flat::orderBy($sort, $order)->where(['district_id' => $user->district_id])->paginate($limit);
        } else {
            $flats = Flat::orderBy($sort, $order)->paginate($limit);
        }

        return $this->response(
            $this->transform->collection($flats, new FlatTransformer)
        );
    }

    public function fullList(Request $request)
    {
        $buildingId = $request->input('building_id');
        $user = Auth::guard('api')->user();

        if ($user && $buildingId) {
            return $this->response(
                $this->transform->collection(Flat::where(['building_id' => $buildingId])->get(), new FlatTransformer)
            );
        } elseif ($user->district_id) {
            return $this->response(
                $this->transform->collection(Flat::where(['district_id' => $user->district_id])->get(), new FlatTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(Flat::where(['district_id' => null])->get(), new FlatTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FlatRequest $request)
    {
        if ($this->isDistrictAdmin()) {
            $params = $request->only(
                'building_id',
                'house_type_id',
                'name',
                'number',
                'area',
                'owner_1_name',
                'owner_1_document_type',
                'owner_1_document_number',
                'owner_1_phone',
                'owner_2_name',
                'owner_2_document_type',
                'owner_2_document_number',
                'owner_2_phone'
            );
            
            $flat = Flat::create($params);
            $flat->district_id = Auth::guard('api')->user()->district_id;
            $flat->save();

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
        $flat = Flat::find($id);

        if (! $flat) {
            return $this->responseWithNotFound('Flat not found');
        }

        return $this->response(
            $this->transform->item($flat, new FlatTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FlatRequest $request, $id)
    {
        $flat = Flat::find($id);

        if (! $flat) {
            return $this->responseWithNotFound('Flat not found');
        }

        if ($this->isDistrictAdmin()) {
            $flat->building_id = $request->get('building_id');
            $flat->house_type_id = $request->get('house_type_id');
            $flat->name = $request->get('name');
            $flat->number = $request->get('number');
            $flat->area = $request->get('area');
            $flat->owner_1_name = $request->get('owner_1_name');
            $flat->owner_1_document_type = $request->get('owner_1_document_type');
            $flat->owner_1_document_number = $request->get('owner_1_document_number');
            $flat->owner_1_phone = $request->get('owner_1_phone');
            $flat->owner_2_name = $request->get('owner_2_name');
            $flat->owner_2_document_type = $request->get('owner_2_document_type');
            $flat->owner_2_document_number = $request->get('owner_2_document_number');
            $flat->owner_2_phone = $request->get('owner_2_phone');
            $flat->save();

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
        $flat = Flat::find($id);

        if (! $flat) {
            return $this->responseWithNotFound('Flat not found');
        }

        if ($this->isDistrictAdmin()) 
        {
            $flat->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}