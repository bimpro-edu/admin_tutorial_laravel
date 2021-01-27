<?php

use Illuminate\Database\Seeder;
use ThaoHR\Permission;
use ThaoHR\Role;

class CampaignPermissionsSeeder extends Seeder
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
            'name' => 'campaigns.manage',
            'display_name' => 'Manage campaigns',
            'description' => 'Manage campaigns',
            'removable' => false
        ]);

        $adminRole->attachPermission($permission);
    }
}
