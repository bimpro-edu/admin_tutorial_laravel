<?php

use Illuminate\Database\Seeder;
use ThaoHR\Permission;
use ThaoHR\Role;

class SaleStagePermissionsSeeder extends Seeder
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
            'name' => 'sale-stages.manage',
            'display_name' => 'Manage Sale Stages',
            'description' => 'Manage Sale Stages',
            'removable' => false
        ]);

        $adminRole->attachPermission($permission);
    }
}
