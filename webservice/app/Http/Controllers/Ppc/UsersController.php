<?php

namespace App\Http\Controllers\Ppc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\UserRequest;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;
use App\Models\PropertyCompany;
use App\User;

class UsersController extends ApiController
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
            $users = User::orderBy($sort, $order)
                ->where([
                    'role_id' => 4,
                    'district_id' => $districtId,
                ])->paginate($limit);
        } else {
            $users = User::orderBy($sort, $order)
                ->where([
                    'role_id' => 4,
                    'property_company_id' => $this->getPropertyCompanyId()
                ])->paginate($limit);
        }

        if ($this->isPropertyCompanyAdmin()) {
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

    public function fullList(Request $request)
    {
        $districtId = $request->input('dct_id');

        if ($districtId) {
            return $this->response(
                $this->transform->collection(User::where([
                    'role_id' => 3,
                    'district_id' => $districtId,
                ]), new UserTransformer)
            );
        } else {
            return $this->response(
                $this->transform->collection(User::where([
                    'role_id' => 3,
                    'property_company_id' => $this->getPropertyCompanyId()
                ]), new UserTransformer)
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if ($this->isPropertyCompanyAdmin()) {
            $params = $request->only('name', 'username', 'phone', 'address', 'role_id', 'district_id');
            $ppc = PropertyCompany::find($this->getPropertyCompanyId());
            $opc = $ppc->operatingCompany;
            $params['operating_company_id'] = $opc->id;
            $params['property_company_id'] = $this->getPropertyCompanyId();
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

        if ($this->isPropertyCompanyAdmin()) {
            $user->name = $request->get('name');
            $user->username = $request->get('username');
            $user->phone = $request->get('phone');
            $user->address = $request->get('address');
            $user->role_id = $request->get('role_id');
            $user->district_id = $request->get('district_id');
            // $user->operating_company_id = $this->getPropertyCompanyId();

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

        if ($this->isPropertyCompanyAdmin()) 
        {
            $user->delete();
            
            return $this->response(['result' => 'success']);
        }
        else{
            return $this->response(['result' => 'failure']);
        }
    }
}
