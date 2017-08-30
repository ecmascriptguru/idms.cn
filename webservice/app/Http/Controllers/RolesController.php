<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\RoleRequest;
use App\Transformers\RoleTransformer;


class RolesController extends ApiController
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

        $roles = Role::orderBy($sort, $order)->paginate($limit);

        return $this->response(
            $this->transform->collection($roles, new RoleTransformer)
        );
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
        Role::create($request->only('name'));

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

        $role->name = $request->get('name');
        $role->save();

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
        $role = Role::find($id);

        if (! $role) {
            return $this->responseWithNotFound('Role not found');
        }

        $role->delete();

        return $this->response(['result' => 'success']);
    }
}
