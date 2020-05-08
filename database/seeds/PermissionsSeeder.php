<?php

use App\Group;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Schema;

class PermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Role::truncate();
        User::truncate();
        Group::truncate();
        \DB::table('model_has_permissions')->truncate();
        \DB::table('model_has_roles')->truncate();
        Schema::enableForeignKeyConstraints();

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create restaurants']);
        Permission::create(['name' => 'see restaurants']);
        Permission::create(['name' => 'delete restaurants']);
        Permission::create(['name' => 'edit restaurants']);

        Permission::create(['name' => 'create halls']);
        Permission::create(['name' => 'see halls']);
        Permission::create(['name' => 'delete halls']);
        Permission::create(['name' => 'edit halls']);

        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'see roles']);
        Permission::create(['name' => 'delete roles']);
        Permission::create(['name' => 'edit roles']);

        Permission::create(['name' => 'create groups']);
        Permission::create(['name' => 'see groups']);
        Permission::create(['name' => 'delete groups']);
        Permission::create(['name' => 'edit groups']);

        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'see users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'edit users']);


        // create roles and assign existing permissions

        // gets all permissions via Gate::before rule; see AuthServiceProvider

        $role1 = Role::create(['name' => 'super-admin']);

        $role2 = Role::create(['name' => 'manager']);

        $role3 = Role::create(['name' => 'supervisor']);

        $role4 = Role::create(['name' => 'stuff']);

        // create demo users
        $user1 = Factory(App\User::class)->create([
            'name' => 'Super-Admin',
            'email' => 'superadmin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1234')
        ]);
        $user1->assignRole($role1);

        $user2 = Factory(App\User::class)->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1234')
        ]);
        $user2->assignRole($role2);

        $user3 = Factory(App\User::class)->create([
            'name' => 'Supervisor',
            'email' => 'supervisor@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1234')
        ]);
        $user3->assignRole($role3);

        $user4 = Factory(App\User::class)->create([
            'name' => 'Stuff',
            'email' => 'stuff@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1234')
        ]);
        $user4->assignRole($role4);

        // create groups
        $group1 = App\Group::create([
            'group_name' => 'Super Group',
            'status' => 1
        ]);
        $user3->groups()->attach($group1);

        $group2 = App\Group::create([
            'group_name' => 'Stuff',
            'status' => 1
        ]);

        $user1->groups()->attach($group1);
        $user2->groups()->attach($group1);
        $user3->groups()->attach($group1);
        $user4->groups()->attach($group2);
    }
}
