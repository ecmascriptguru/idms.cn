<?php

namespace App\Http\Controllers\Op;

use Illuminate\Support\Facades\Auth;
use App\Models\OperatingCompany;
use Illuminate\Http\Request;
use App\Http\Requests\OperatingCompanyRequest;
use App\Transformers\OperatingCompanyTransformer;
use App\Http\Controllers\ApiController;

class ShopController extends ApiController
{
    public function isOperatingCompanyAdmin() {
        $user = Auth::guard('api')->user();

        return ($user && $user->role) ? $user->role->id === 2 : false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();
        $operatingCompany = OperatingCompany::find($user->operating_company_id);

        if (! $operatingCompany) {
            return $this->responseWithNotFound('OperatingCompany not found');
        }

        return $this->response(
            $this->transform->item($operatingCompany, new OperatingCompanyTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::guard('api')->user();
        $operatingCompany = OperatingCompany::find($user->operating_company_id);

        if (! $operatingCompany) {
            return $this->responseWithNotFound('OperatingCompany not found');
        }

        if ($this->isOperatingCompanyAdmin()) {
            $operatingCompany->shop = $request->get('shop');
            $operatingCompany->save();

            return $this->response(['result' => 'success']);
        } else {
            return $this->response(['result' => 'failure']);
        }
    }
}
