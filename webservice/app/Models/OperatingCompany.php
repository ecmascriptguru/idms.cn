<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\User;

class OperatingCompany extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'address',
        'phone',
        'contact'
    ];

    public function users()
    {
        $role = Role::first();
        return User::where(['role_id' => $role->id, 'organization_id' => $this->id])->get();
    }
}
