<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Http\Requests\RoleRequest;
use App\Transformers\RoleTransformer;
use App\Http\Controllers\ApiController;


class RolesController extends ApiController
{
    public function isAdmin() {
        $user = Auth::guard('api')->user();
        $role = $user->role;

        return $role->id === 1;
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
        
        $roles = Role::orderBy($sort, $order)->paginate($limit);

        if ($this->isAdmin()) {
            return $this->response(
                $this->transform->collection($roles, new RoleTransformer)
            );
        } else {
            $roles = Role::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($roles, new RoleTransformer)
            );
        }
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(Role::all(), new RoleTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        if ($this->isAdmin()) {
        
            Role::create($request->only('name'));

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
        $role = Role::find($id);

        if (! $role) {
            return $this->responseWithNotFound('Role not found');
        }

        return $this->response(
            $this->transform->item($role, new RoleTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = Role::find($id);

        if (! $role) {
            return $this->responseWithNotFound('Role not found');
        }

        if ($this->isAdmin()) {
            $role->name = $request->get('name');
            $role->save();

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
        $role = Role::find($id);

        if (! $role) {
            return $this->responseWithNotFound('Role not found');
        }

        if ($this->isAdmin()) 
        {
            $role->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
