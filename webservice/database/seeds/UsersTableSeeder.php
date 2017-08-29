<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::FirstOrCreate([
            'name' => 'Dev Ops',
            'username' => 'admin',
            'role_id' => 1,
            'password' => Hash::make(123456),
        ]);
    }
}
