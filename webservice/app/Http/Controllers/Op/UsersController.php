<?php

namespace App\Http\Controllers\Op;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersController extends ApiController
{
    public function isOperatingCompanyAdmin() {
        $user = Auth::guard('api')->user();
        // $role = $user->role;

        return (gettype($user) == 'object') ? $user->role->id === 2 : false;
    }

    private function getOperatingCompanyId() {
        $user = Auth::guard('api')->user();
        
        if (gettype($user) == 'object' && $user->organization) {
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
        
        $users = User::orderBy($sort, $order)
                ->where([
                    'role_id' => 3,
                    'operating_company_id' => $this->getOperatingCompanyId()
                ])->paginate($limit);

        if ($this->isOperatingCompanyAdmin()) {
            return $this->response(
                $this->transform->collection($users, new UserTransformer)
            );
        } else {
            $users = User::where(['id' => null])->paginate($limit);
            return $this->response(
                $this->transform->collection($users, new UserTransformer)
            );
        }
    }

    public function fullList()
    {
        return $this->response(
            $this->transform->collection(User::where([
                'role_id' => 3,
                'operating_company_id' => $this->getOperatingCompanyId()
            ]), new UserTransformer)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if ($this->isOperatingCompanyAdmin()) {
            $params = $request->only('name', 'username', 'phone', 'address', 'role_id', 'property_company_id');
            $params['operating_company_id'] = $this->getOperatingCompanyId();
            $params['password'] = Hash::make($request->input('password'));
            User::create($params);

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
        $user = User::find($id);

        if (! $user) {
            return $this->responseWithNotFound('User not found');
        }

        return $this->response(
            $this->transform->item($user, new UserTransformer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return $this->responseWithNotFound('User not found');
        }

        if ($this->isOperatingCompanyAdmin()) {
            $user->name = $request->get('name');
            $user->username = $request->get('username');
            $user->phone = $request->get('phone');
            $user->address = $request->get('address');
            $user->role_id = $request->get('role_id');
            $user->property_company_id = $request->get('property_company_id');
            // $user->operating_company_id = $this->getOperatingCompanyId();

            if (!empty($request->get('password'))) {
                $user->password = Hash::make($request->get('password'));
            }
            $user->save();

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
        $user = User::find($id);

        if (! $user) {
            return $this->responseWithNotFound('User not found');
        }

        if ($this->isOperatingCompanyAdmin()) 
        {
            $user->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
