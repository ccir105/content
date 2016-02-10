<?php

/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/10/16
 * Time: 12:53 PM
 */
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Repository\SupplierRepository;
use Faker\Factory;
use Modules\Supplier\SearchStrategy\ByCountry;
use Modules\Supplier\SearchStrategy\ByProducts;
use Modules\Supplier\SearchStrategy\ByService;

/**
 * @property array searchStrategys
 */
class SupplierCountryTest extends TestCase
{
    use DatabaseTransactions;

    function add_supplier_contry($inputs = []){

        $faker = Factory::create();

        $serviceRepo = new ServiceRepository();

        $service1 = $serviceRepo->getByClass('fuel');

        $service2 = $serviceRepo->getByClass('tank');

        $supplierRepo =  new SupplierRepository();

        $supplierInput = array_merge([
            'company_name' => $faker->company,
            'first_name' => $faker->firstNameMale,
            'last_name' => $faker->lastName,
            'email_address' => $faker->email,
            'phone' => $faker->phoneNumber,
            'road' => $faker->address,
            'postal_code' => $faker->postcode,
            'profile' => [
                'company_name' => $faker->company,
                'website'=>"www.test.com",
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'road'=> $faker->address,
                'address'=>$faker->address,
                'zip_code'=>$faker->postcode,
                'description'=>$faker->sentence
            ],
            'products' => array_merge( $service1->products->lists('id')->toArray() , $service2->products->lists('id')->toArray() ),

        ],$inputs);

        $this->supplier = $supplierRepo->store($supplierInput);

        return $this->supplier;
    }


    public function test_add_supplier(){

        $supplierInput['company_name'] = 'ABC Traders';

        $supplierInput['products'] = ['1','2','3'];

        $supplier = $this->add_supplier_contry($supplierInput);

        $this->assertEquals($this->supplier->company_name,$supplierInput['company_name']);

        $this->assertEquals($this->supplier->products->lists('pivot.product_id')->toArray(),$supplierInput['products']);

    }
    public function test_add_edit_country(){
        $supplier = $this->add_supplier_contry();

        $inputs = [
            [
                'id' => 1,
                'zip' => array(
                    [
                        'zip_from' => '123',
                        'zip_to' => '345'
                    ],
                    [
                        'zip_from' => '3546',
                        'zip_to' => '54565'
                    ]
                )
            ],
            [
                'id' => 2,
                'zip' => array(
                    [
                        'zip_from' => '12365',
                        'zip_to' => '34225'
                    ],
                    [
                        'zip_from' => '354236',
                        'zip_to' => '456522'
                    ]
                )
            ]
        ];

        $editInput = [
            [
                'id' => 2,
                'zip' => array(
                    [
                        'zip_from' => '777',
                        'zip_to' => '999'
                    ],
                    [
                        'zip_from' => '3333',
                        'zip_to' => '4444'
                    ]
                )
            ]
        ];

        $supplierRepo =  new SupplierRepository();

        $result = $supplierRepo->saveCountry($inputs, $supplier);

        $countries = $supplier->country;

        $countries = array_values(array_unique($countries->lists('pivot.country_id')->toArray()));

        $this->assertEquals(true,$result);

        $this->assertEquals($countries, ['1','2']);

        $result = $supplierRepo->editCountry($editInput, $supplier);

        $countries = $supplier->country()->get();

        $countries = array_values(array_unique($countries->lists('pivot.country_id')->toArray()));

        $this->assertEquals($countries, ['2']);
    }

    public function test_search_country(){


        $serviceRepo = new ServiceRepository();

        $supplier1 = $this->add_supplier_contry([
            'products' => [ 1, 2 ]
        ]);

//        echo 'SUp1--- '. $supplier1->id . PHP_EOL;

        $supplier2 = $this->add_supplier_contry([
            'products' => [ 6, 9, 11 ]
        ]);

        $supplier3 = $this->add_supplier_contry([
            'products' => [ 5 , 9 ]
        ]);

        $builder = new \Modules\Supplier\SearchStrategy\SearchBuilder([
            'service' => 3,
            'products' => [ 11 ]
        ]);

        $result = $this->initSearch( $builder );

        $this->assertEquals( [ $supplier2->id ], $result );

        $builder = new \Modules\Supplier\SearchStrategy\SearchBuilder([
            'service' => 1,
            'products' => [ 3 ]
        ]);

        $result = $this->initSearch( $builder );

        $this->assertFalse($result);
    }

    public function test_country_zip_code(  ) {

        $supplier1 = $this->add_supplier_contry([
            'products' => [ 1, 2 , 5, 9, 11 ,14 ],
            'country'=> array(
                [
                    'id' => 1,
                    'zip' => array(
                         [
                            'zip_from' => '100',
                            'zip_to' => '500'
                         ],
                         [
                            'zip_from' => '2000',
                            'zip_to' => '4000'
                         ]
                    )
                ],
                [
                    'id' => 2,
                    'zip' => array(
                        [
                            'zip_from' => '1000',
                            'zip_to' => '5000'
                        ],
                        [
                            'zip_from' => '8000',
                            'zip_to' => '9000'
                        ]
                    )
                ]
            )]);


        $supplier2 = $this->add_supplier_contry([
            'products' => [ 1 , 5, 9, 11 ,14 ],
            'country'=> array([
                'id' => 2,
                'zip' => array(
                    [
                        'zip_from' => '4000',
                        'zip_to' => '9000'
                    ],
                    [
                        'zip_from' => '8500',
                        'zip_to' => '12000'
                    ]
                )
            ])
        ]);

        $supplier3 = $this->add_supplier_contry([
                'products' => [ 1, 2 , 5 ,14 ],
                'country'=> array(
                    [
                        'id' => 1,
                        'zip' => array(
                            [
                                'zip_from' => '400',
                                'zip_to' => '900'
                            ],
                            [
                                'zip_from' => '3800',
                                'zip_to' => '9000'
                            ]
                        )
                    ]
                )
         ]);

        $builder = new \Modules\Supplier\SearchStrategy\SearchBuilder([
            'service' => 1,
            'products' => [ 1 ],
            'country_id' => 1,
            'zip_code' => '454'
        ]);

        $result = $this->initSearch( $builder );

        $this->assertEquals([$supplier1->id, $supplier3->id],$result);
    }

    public function initSearch($builder){

        $searchStrategys = array(
            new ByService(),
            new ByProducts(),
            new ByCountry()
        );

        foreach($searchStrategys as $strategy){

            if($strategy->isValid( $builder->getRequest() ) ){
                $builder->add( $strategy );
            }
        }

        return $builder->getResults(true);
    }

}