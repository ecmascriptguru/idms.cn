<?php

namespace App\Http\Controllers\Ppc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\District;
use App\Models\PropertyCompany;
use App\Http\Requests\DistrictRequest;
use App\Transformers\DistrictTransformer;
use App\Http\Controllers\ApiController;

class DistrictsController extends ApiController
{
    public function isPropertyCompanyAdmin() {
        $user = Auth::guard('api')->user();

        return ($user && $user->role) ? $user->role->id === 3 : false;
    }

    private function getPropertyCompanyId() {
        $user = Auth::guard('api')->user();
        
        if ($user && $user->property_company_id) {
            return $user->property_company_id;
        } else {
            return null;
        }
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

        $districts = District::orderBy($sort, $order)
            ->where(['property_company_id' => $this->getPropertyCompanyId()])->paginate($limit);

        return $this->response(
            $this->transform->collection($districts, new DistrictTransformer)
        );
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(District::where(['property_company_id' => $this->getPropertyCompanyId()])->get(), new DistrictTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistrictRequest $request)
    {
        if ($this->isPropertyCompanyAdmin()) {
            $param = $request->only('name', 'short_name', 'province', 'city', 'contact', 'phone', 'address', 'property_company_id');
            $ppc = PropertyCompany::find($this->getPropertyCompanyId());
            $opc = $ppc->operatingCompany;
            $param['operating_company_id'] = $opc->id;
            $param['property_company_id'] = $this->getPropertyCompanyId();
            District::create($param);
            
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
        $district = District::find($id);

        if (! $district) {
            return $this->responseWithNotFound('District not found');
        }

        return $this->response(
            $this->transform->item($district, new DistrictTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DistrictRequest $request, $id)
    {
        $district = District::find($id);

        if (! $district) {
            return $this->responseWithNotFound('District not found');
        }

        if ($this->isPropertyCompanyAdmin()) {
            $district->name = $request->get('name');
            $district->short_name = $request->get('short_name');
            $district->province = $request->get('province');
            $district->city = $request->get('city');
            $district->contact = $request->get('contact');
            $district->phone = $request->get('phone');
            $district->address = $request->get('address');
            $district->save();

            return $this->response(['result' => 'success']);
        } else {
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
        $district = District::find($id);

        if (! $district) {
            return $this->responseWithNotFound('District not found');
        }

        if ($this->isPropertyCompanyAdmin()) { 
            $district->delete();
            
            return $this->response(['result' => 'success']);
        } else {
            return $this->response(['result' => 'failure']);
        }
    }
    
    public function reminder(Request $request) {
        $district = District::find($request->get('id'));

        if (! $district) {
            return $this->responseWithNotFound('District not found');
        }

        $district->reminder = $request->get('reminder');
        $district->save();

        return $this->response(['result' => 'success']);
    }
}
