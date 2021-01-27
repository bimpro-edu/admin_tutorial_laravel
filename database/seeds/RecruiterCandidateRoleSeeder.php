<?php

use ThaoHR\Role;
use Illuminate\Database\Seeder;

class RecruiterCandidateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 3,
            'name' => 'recruiter',
            'display_name' => 'Recruiter',
            'description' => 'Recruiter.',
            'removable' => false
        ]);

        Role::create([
            'id' => 4,
            'name' => 'candidate',
            'display_name' => 'Candidate',
            'description' => 'Candidate.',
            'removable' => false
        ]);
    }
}
