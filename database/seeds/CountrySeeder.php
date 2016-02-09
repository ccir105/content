<?php

use Illuminate\Database\Seeder;
use Modules\Supplier\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Country::truncate();

        $countries = [
            ['name' => 'Deutschland'],
            ['name' => 'Schweiz'],
            ['name' => 'Österreich']
        ];

        foreach ($countries as $country) {
        	Country::create($country);
        }
    }
}
