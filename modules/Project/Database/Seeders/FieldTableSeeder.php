<?php namespace Modules\Project\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Project\Entities\Field;

class FieldTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

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

		foreach($fields as $field){
			$field = new Field($field);
			$field->save();
		}
	}
}