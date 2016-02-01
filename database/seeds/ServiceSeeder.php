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
    			'fuel oil',
    			'diesel',
    			'wood pellets'
    		],
    		'Heizugen:heater' => [
    			'oil heating',
    			'heat pump',
    			'pellet',
    			'gas heating',
    			'Fireplace',
    			'Other'
    		],
    		'Tankervision:tank' => [
    			'fuel oil',
    			'diesel',
    			'wood pellets'
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

        $country = [
            'name' => 'Nepal',
            'name' => 'China',
            'name' => 'Bhutan'
        ];

        Country::create($country);

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
