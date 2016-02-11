<?php

use Illuminate\Database\Seeder;
use Modules\Supplier\Service;
use Modules\Supplier\Product;
use Modules\Supplier\Supplier;
use Faker\Factory;
use Modules\Supplier\Country;


class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$list = [
    		'Energie:fuel' => [
    			'Heizöl',
    			'Diesel',
    			'Holzpellets'
    		],
    		'Heizugen:heater' => [
    			'Ölheizung',
    			'Wärmepumpe',
    			'Pelletsheizung',
    			'Gasheizung',
    			'Kamin',
    			'Sonstiges'
    		],
    		'Tankervision:tank' => [
    			'Heizöl',
    			'Diesel',
    			'Holzpellets'
    		],
    	];

    	foreach ( $list as $service_name => $products ) {
    		$service = new Service;
            list($name, $class) = explode(':',$service_name);
    		$service->name = $name;
            $service->class = $class;
    		$service->save();
    		
    		foreach ($products as $product_name) {
    			$product = new Product;
    			$product->service_id = $service->id;
    			$product->name = $product_name;
    			$product->save();
    		}
    	}

		$faker = Factory::create();

        // $country = [
        //     ['name' => 'Nepal'],
        //     ['name' => 'China'],
        //     ['name' => 'Bhutan']
        // ];

        // Country::create($country);
        
        $user = new App\User;

        $user->firstOrcreate([
            'name' => 'Test User',
            'email' => 'admin@test.com',
            'password' => Hash::make('test')
        ]);

		// for($i = 0;$i<=3;$i++){

		// 	$supplier = [
		// 		'first_name' => $faker->firstNameMale,
		// 		'last_name' => $faker->lastName,
		// 		'company_name' => $faker->company,
		// 		'email_address' => $faker->email,
		// 		'phone' => $faker->phoneNumber,
		// 		'road' => $faker->address,
		// 		'postal_code' => $faker->postcode,
		// 		'country_id' => $i
		// 	];

		// 	Supplier::create($supplier);
		// }
    }
}
