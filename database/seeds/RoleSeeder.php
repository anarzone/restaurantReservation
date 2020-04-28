<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();

        $AdminRole = Role::create([
           'name' => Role::ADMIN_ROLE
        ]);

        $StuffRole = Role::create([
           'name' => Role::STUFF_ROLE
        ]);
    }
}
