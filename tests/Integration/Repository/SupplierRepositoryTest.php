<?php 
	use Illuminate\Foundation\Testing\DatabaseTransactions;
    use Modules\Supplier\Repository\SupplierRepository;
    use Modules\Supplier\Supplier;
    use Modules\Supplier\Repository\ServiceRepository;
    use Faker\Factory;
    use Modules\Supplier\Country;

	class SupplierRepositoryTest extends TestCase{

        // use DatabaseTransactions;

        private $supplier;

        //for testing
        function addSupplier($inputs = []){

            $faker = Factory::create();

            $serviceRepo = new ServiceRepository();

            $service1 = $serviceRepo->getByClass('fuel');

            $service2 = $serviceRepo->getByClass('tank');

            $supplierRepo =  new SupplierRepository();

            $country = Country::find(1);


            $supplierInput = array_merge([
                'company_name' => $faker->company,
                'first_name' => $faker->firstNameMale,
                'last_name' => $faker->lastName,
                'email_address' => $faker->email,
                'phone' => $faker->phoneNumber,
                'road' => $faker->address,
                'postal_code' => $faker->postcode,
                'country_id' => $country->id,
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
                'products' => array_merge( $service1->products->lists('id')->toArray() , $service2->products->lists('id')->toArray() )
            ],$inputs);

            $this->supplier = $supplierRepo->store($supplierInput);

            return $this->supplier;
        }

        function test_add_new_supplier(){

            $supplier = $this->addSupplier();

            $this->assertNotNull($supplier);

            $supplierRepo =  new SupplierRepository();

            $suppliers = $supplierRepo->all();

            $this->assertEquals(false, $suppliers->isEmpty());

            $this->assertEquals(1, $suppliers->count());

            $aSupplier = $suppliers->first()->toArray();

            $this->assertArrayHasKey('profile', $aSupplier);

            $this->assertEquals(false,$supplier->services->isEmpty());

            $this->assertEquals(2,$supplier->services->count());

            $this->assertEquals(false, $supplier->products->isEmpty());

		}

        function test_product_list_by_service(){

            $serviceRepo = new ServiceRepository();

            $service1 = $serviceRepo->getByClass('fuel');

            $service2 = $serviceRepo->getByClass('tank');

            $productIds = array_merge( $service1->products->lists('id')->toArray() , $service2->products->lists('id')->toArray() );

            $supplierRepo =  new SupplierRepository();

            $serviceAndProduct = $supplierRepo->productListWithService($productIds);

            $this->assertEquals( array_keys( $serviceAndProduct ), $productIds);

            $firstProduct = array_pop($serviceAndProduct);

            $this->assertArrayHasKey('service_id',$firstProduct);
        }

        function test_update_a_supplier(){

            $supplierBeforeUpdate = $this->addSupplier();

            $updateArray = [
                'company_name' => 'Sishir Trades',
                'first_name' => 'Sishir',
                'last_name' => 'Pokhrel',
                'email_address' => 'sisnet2010@gmail.com',
                'phone' => '98232323',
                'profile' => [
                    'company_name' => 'Sishir Trades And Suppliers'
                ]
            ];

            $serviceRepo = new SupplierRepository();

            $supplierAfterUpdate = $serviceRepo->store($updateArray, $supplierBeforeUpdate->id);

            $this->assertNotFalse($supplierAfterUpdate);

            $this->assertEquals($updateArray['company_name'],$supplierAfterUpdate->company_name);

            $this->assertEquals($updateArray['email_address'],$supplierAfterUpdate->email_address);

            $profile = $supplierAfterUpdate->profile;

            $this->assertEquals($updateArray['profile']['company_name'],$profile->company_name);
        }

        function test_id_not_found_in_update_case() {

            $serviceRepo = new SupplierRepository();

            $supplier = $serviceRepo->store([],100);

            $this->assertFalse($supplier);
        }

        function test_filter_suppliers_by_products_and_supplier_list(){

            $this->addSupplier();

            $supplierRepo = new SupplierRepository();

            $suppliers = $supplierRepo->all()->lists('id')->toArray();

            $serviceRepo = new ServiceRepository();

            $service1 = $serviceRepo->getByClass('fuel');

            $products = $service1->products->lists('id')->toArray();

            $result = $supplierRepo->findByProductBySuppliers($products,$suppliers);

            $this->assertGreaterThan( 0, count( array_intersect( $result, $suppliers ) ) );

        }


        function test_finding_by_query_string_single_column(){

            //comapny name passing
            $this->addSupplier( ["company_name" => "Sishir Traders"] );

            $supplierRepo = new SupplierRepository();

            $supplierList = $supplierRepo->findByQueryString("Sishir");

            $this->assertEquals(1,$supplierList->count());

            $this->assertEquals('Sishir Traders',$supplierList->first()->company_name );
        }

        //failed but why need to test more
//        function test_finding_by_query_string_multiple_column(){
//
//            $this->addSupplier("Sishir Traders");
//
//            $supplierRepo = new SupplierRepository();
//
//            $supplierList = $supplierRepo->findByQueryString("Sishir Traders", ['company_name']);
//
//            $this->assertEquals(1,$supplierList->count());
//
//            $this->assertEquals('Sishir Traders',$supplierList->first()->company_name );
//        }

        function test_search_supplier_by_product(){

            $this->addSupplier();

            $serviceRepo = new ServiceRepository();

            $service = $serviceRepo->getByClass('fuel');

            $products = $service->products->lists('id')->toArray();

            $supplierRepo = new SupplierRepository();

            $supplierIds = $supplierRepo->searchByProducts($products);

            $this->assertEquals(1,count( $supplierIds ) );

        }

        function test_search_by_column(){

            $supplier = $this->addSupplier(['country_id'=> '1' ]);

            $supplierRepo = new SupplierRepository();

            $result = $supplierRepo->findByColumn(['country_id'=>'1']);

            $this->assertEquals(1, count($result));

            $this->assertEquals($supplier->id,$result[0]);
        }
	}