<?php

use Illuminate\Database\Seeder;
use Modules\UserManagement\Entities\Role;

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

		if (!file_exists(storage_path('seed')))
		{
			file_put_contents(storage_path('seed'), 'seed :' . time());

			$user = new \App\User();
			$user->name = "admin";
			$user->email = "admin@admin.com";
			$user->password = "admin";
			$user->save();

			$owner = new Role();
			$owner->name = 'admin';
			$owner->display_name = 'Admin'; // optional
			$owner->description = 'Have Control to all resources'; // optional
			$owner->save();

			$owner = new Role();
			$owner->name = 'client';
			$owner->display_name = 'Client'; // optional
			$owner->description = 'Normal User basically client'; // optional
			$owner->save();

			$owner = new Role();
			$owner->name = 'project_manager';
			$owner->display_name = 'Client'; // optional
			$owner->description = 'Project Manger'; // optional
			$owner->save();

			$owner = new Role();
			$owner->name = 'developer';
			$owner->display_name = 'Developer'; // optional
			$owner->description = 'Developer who write codes'; // optional
			$owner->save();


			$fields = [
				array(
					'type' => 'text',
					'title' => 'A Text Box',
					'description' => ''
				),
				array(
					'type' => 'file',
					'title' => 'A File Input',
					'description' => ''
				),
				array(
					'type' => 'image',
					'title' => 'A Image Box',
					'description' => ''
				),
				array(
					'type' => 'textarea',
					'title' => 'A Text Area Box',
					'description' => ''
				),
			];

			foreach ($fields as $field) {
				$field = new Modules\Project\Entities\Field($field);
				$field->save();
			}

		}

		factory(App\User::class, 10)->create()->each(function($user){
			$user->attachRole(Role::all()->random()->first());
		});

		factory(Modules\Project\Entities\Project::class, 10)->create();
		factory(Modules\Project\Entities\Page::class, 10)->create();
		factory(Modules\Project\Entities\FieldGroup::class, 10)->create();
		factory(Modules\Project\Entities\FieldValue::class, 10)->create();


	}
}
