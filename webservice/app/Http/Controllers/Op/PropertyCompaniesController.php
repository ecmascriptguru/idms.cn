<?php

namespace App\Http\Controllers\Op;

use Illuminate\Support\Facades\Auth;
use App\Models\PropertyCompany;
use App\Http\Requests\PropertyCompanyRequest;
use App\Transformers\PropertyCompanyTransformer;
use App\Http\Controllers\ApiController;

class PropertyCompaniesController extends ApiController
{
    public function isOperatingCompanyAdmin() {
        $user = Auth::guard('api')->user();

        return ($user && $user->role) ? $user->role->id === 2 : false;
    }

    private function getOperatingCompanyId() {
        $user = Auth::guard('api')->user();
        
        if ($user->organization) {
            return $user->organization->id;
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

        $propertyCompanies = PropertyCompany::orderBy($sort, $order)
            ->where(['operating_company_id' => $this->getOperatingCompanyId()])->paginate($limit);

        return $this->response(
            $this->transform->collection($propertyCompanies, new PropertyCompanyTransformer)
        );
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(PropertyCompany::where(['operating_company_id' => $this->getOperatingCompanyId()])->get(), new PropertyCompanyTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyCompanyRequest $request)
    {
        if ($this->isOperatingCompanyAdmin()) {
            $param = $request->only('name', 'short_name', 'contact', 'phone', 'address', 'operating_company_id');
            $param['operating_company_id'] = $this->getOperatingCompanyId();
            PropertyCompany::create($param);
            
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
        $propertyCompany = PropertyCompany::find($id);

        if (! $propertyCompany) {
            return $this->responseWithNotFound('PropertyCompany not found');
        }

        return $this->response(
            $this->transform->item($propertyCompany, new PropertyCompanyTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyCompanyRequest $request, $id)
    {
        $propertyCompany = PropertyCompany::find($id);

        if (! $propertyCompany) {
            return $this->responseWithNotFound('PropertyCompany not found');
        }

        if ($this->isOperatingCompanyAdmin()) {
            $propertyCompany->name = $request->get('name');
            $propertyCompany->short_name = $request->get('short_name');
            $propertyCompany->contact = $request->get('contact');
            $propertyCompany->phone = $request->get('phone');
            $propertyCompany->address = $request->get('address');
            $propertyCompany->save();

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
        $propertyCompany = PropertyCompany::find($id);

        if (! $propertyCompany) {
            return $this->responseWithNotFound('PropertyCompany not found');
        }

        if ($this->isOperatingCompanyAdmin()) { 
            $propertyCompany->delete();
            
            return $this->response(['result' => 'success']);
        } else {
            return $this->response(['result' => 'failure']);
        }
    }

    public function getOperatingCompany() {
        $user = Auth::guard('api')->user();
        $operatingCompany = $user->operatingCompany;
        return $this->response(compact('operatingCompany'));
    }
}
