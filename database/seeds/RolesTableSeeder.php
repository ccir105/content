<?php

use Illuminate\Database\Seeder;
use Modules\Management\Entities\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	// Role::truncate();
      	$owner = new Role();
		$owner->name         = 'admin';
		$owner->display_name = 'Admin'; // optional
		$owner->description  = 'Have Control to all resources'; // optional
		$owner->save();

		$owner = new Role();
		$owner->name         = 'client';
		$owner->display_name = 'Client'; // optional
		$owner->description  = 'Normal User basically client'; // optional
		$owner->save();

		$owner = new Role();
		$owner->name         = 'project_manager';
		$owner->display_name = 'Client'; // optional
		$owner->description  = 'Project Manger'; // optional
		$owner->save();

		$owner = new Role();
		$owner->name         = 'developer';
		$owner->display_name = 'Developer'; // optional
		$owner->description  = 'Developer who write codes'; // optional
		$owner->save();

    }
}
