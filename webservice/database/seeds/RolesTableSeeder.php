<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::FirstOrCreate([
            'name' => 'Admin',
        ]);

        Role::FirstOrCreate([
            'name' => 'Operating Manager',
        ]);

        Role::FirstOrCreate([
            'name' => 'PC Employee',
        ]);

        Role::FirstOrCreate([
            'name' => 'DC Employee',
        ]);
    }
}
