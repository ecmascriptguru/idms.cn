<?php

namespace App\Http\Controllers;

use App\Models\OperatingCompany;
use App\Http\Requests\OperatingCompanyRequest;
use App\Transformers\OperatingCompanyTransformer;


class OperatingCompaniesController extends ApiController
{
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

        $opCompanies = OperatingCompany::orderBy($sort, $order)->paginate($limit);

        return $this->response(
            $this->transform->collection($opCompanies, new OperatingCompanyTransformer)
        );
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(OperatingCompany::all(), new OperatingCompanyTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OperatingCompanyRequest $request)
    {
        OperatingCompany::create($request->only('name'));

        return $this->response(['result' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $opCompany = OperatingCompany::find($id);

        if (! $opCompany) {
            return $this->responseWithNotFound('OperatingCompany not found');
        }

        return $this->response(
            $this->transform->item($opCompany, new OperatingCompanyTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OperatingCompanyRequest $request, $id)
    {
        $opCompany = OperatingCompany::find($id);

        if (! $opCompany) {
            return $this->responseWithNotFound('OperatingCompany not found');
        }

        $opCompany->name = $request->get('name');
        $opCompany->save();

        return $this->response(['result' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $opCompany = OperatingCompany::find($id);

        if (! $opCompany) {
            return $this->responseWithNotFound('OperatingCompany not found');
        }

        $opCompany->delete();

        return $this->response(['result' => 'success']);
    }
}
