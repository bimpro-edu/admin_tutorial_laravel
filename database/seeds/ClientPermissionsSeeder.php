<?php

use Illuminate\Database\Seeder;
use ThaoHR\Permission;
use ThaoHR\Role;

class ClientPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        $permission = Permission::create([
            'name' => 'clients.manage',
            'display_name' => 'Manage clients',
            'description' => 'Manage clients',
            'removable' => false
        ]);

        $adminRole->attachPermission($permission);
    }
}
