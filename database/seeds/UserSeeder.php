<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $Admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);

        $Stuff = User::create([
            'name' => 'Stuff Test',
            'email' => 'stuff@example.com',
            'password' => Hash::make('stuff123'),
        ]);

        $Admin_Role = Role::where('name', 'admin')->get();
        $Stuff_Role = Role::where('name', 'stuff')->get();

        $Admin->roles()->attach($Admin_Role);
        $Stuff->roles()->attach($Stuff_Role);

    }

}
