<?php

namespace App\Http\Controllers\Ppc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\FeeStandardRequest;
use App\Transformers\FeeStandardTransformer;
use App\Http\Controllers\ApiController;
use App\Models\PropertyCompany;
use App\Models\FeeStandard;

class FeeStandardsController extends ApiController
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
    public function index(Request $request)
    {
        $sort = $this->getSort();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $districtId = $request->input('dct_id');

        if ($districtId) {
            $feeStandards = FeeStandard::orderBy($sort, $order)
                ->where([
                    'district_id' => $districtId,
                ])->paginate($limit);
        } else if ($this->isPropertyCompanyAdmin()) {
            $districts = PropertyCompany::find($this->getPropertyCompanyId())->districts;
            $ids = [];
            foreach($districts as $district) {
                array_push($ids, $district->id);
            }
            $feeStandards = FeeStandard::orderBy($sort, $order)
                ->whereIn('district_id', $ids)->paginate($limit);
        } else {
            $feeStandards = FeeStandard::where(['id' => null])->paginate($limit);
        }

        return $this->response(
            $this->transform->collection($feeStandards, new FeeStandardTransformer)
        );
    }

    public function fullList(Request $request)
    {
        $districtId = $request->input('dct_id');

        if ($districtId) {
            return $this->response(
                $this->transform->collection(FeeStandard::where([
                    'district_id' => $districtId,
                ]), new FeeStandardTransformer)
            );
        } else {
            $districts = PropertyCompany::find($this->getPropertyCompanyId())->districts;
            $ids = [];
            foreach($districts as $district) {
                array_push($ids, $district->id);
            }
            return $this->response(
                $this->transform->collection(FeeStandard::whereIn('district_id',$ids), new FeeStandardTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeeStandardRequest $request)
    {
        if ($this->isPropertyCompanyAdmin()) {
            $params = $request->only(
                'district_id',
                'house_type_id',
                'property_management_fee',
                'water_fee',
                'electricity_fee',
                'parking_fee',
                'gas_fee',
                'heating_fee',
                'internet_fee',
                'custom_fee_1_type_id',
                'custom_fee_1_name',
                'custom_fee_1_rate',
                'custom_fee_2_type_id',
                'custom_fee_2_name',
                'custom_fee_2_rate',
                'custom_fee_3_type_id',
                'custom_fee_3_name',
                'custom_fee_3_rate'
            );
            FeeStandard::create($params);

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
        $feeStandard = FeeStandard::find($id);

        if (! $feeStandard) {
            return $this->responseWithNotFound('FeeStandard not found');
        }

        return $this->response(
            $this->transform->item($feeStandard, new FeeStandardTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeeStandardRequest $request, $id)
    {
        $feeStandard = FeeStandard::find($id);

        if (! $feeStandard) {
            return $this->responseWithNotFound('FeeStandard not found');
        }

        if ($this->isPropertyCompanyAdmin()) {
            $feeStandard->district_id = $request->get('district_id');
            $feeStandard->house_type_id = $request->get('house_type_id');
            $feeStandard->property_management_fee = $request->get('property_management_fee');
            $feeStandard->water_fee = $request->get('water_fee');
            $feeStandard->electricity_fee = $request->get('electricity_fee');
            $feeStandard->parking_fee = $request->get('parking_fee');
            $feeStandard->gas_fee = $request->get('gas_fee');
            $feeStandard->heating_fee = $request->get('heating_fee');
            $feeStandard->internet_fee = $request->get('internet_fee');
            $feeStandard->custom_fee_1_type_id = $request->get('custom_fee_1_type_id');
            $feeStandard->custom_fee_1_name = $request->get('custom_fee_1_name');
            $feeStandard->custom_fee_1_rate = $request->get('custom_fee_1_rate');
            $feeStandard->custom_fee_2_type_id = $request->get('custom_fee_2_type_id');
            $feeStandard->custom_fee_2_name = $request->get('custom_fee_2_name');
            $feeStandard->custom_fee_2_rate = $request->get('custom_fee_2_rate');
            $feeStandard->custom_fee_3_type_id = $request->get('custom_fee_3_type_id');
            $feeStandard->custom_fee_3_name = $request->get('custom_fee_3_name');
            $feeStandard->custom_fee_3_rate = $request->get('custom_fee_3_rate');

            $feeStandard->save();

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
        $feeStandard = FeeStandard::find($id);

        if (! $feeStandard) {
            return $this->responseWithNotFound('FeeStandard not found');
        }

        if ($this->isPropertyCompanyAdmin()) 
        {
            $feeStandard->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
