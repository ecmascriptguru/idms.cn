<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\AccountType;
use App\Http\Requests\AccountTypeRequest;
use App\Transformers\AccountTypeTransformer;
use App\Http\Controllers\ApiController;


class AccountTypesController extends ApiController
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
        
        $types = AccountType::orderBy($sort, $order)->paginate($limit);

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($types, new AccountTypeTransformer)
            );
        } else {
            $types = AccountType::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($types, new AccountTypeTransformer)
            );
        }
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(AccountType::all(), new AccountTypeTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountTypeRequest $request)
    {
        if ($this->isAdmin()) {
        
            AccountType::create($request->only('name'));

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
        $type = AccountType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('AccountType not found');
        }

        return $this->response(
            $this->transform->item($type, new AccountTypeTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountTypeRequest $request, $id)
    {
        $type = AccountType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('AccountType not found');
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
        $type = AccountType::find($id);

        if (! $type) {
            return $this->responseWithNotFound('AccountType not found');
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
