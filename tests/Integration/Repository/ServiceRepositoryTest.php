<?php 
	use Illuminate\Foundation\Testing\DatabaseTransactions;
	use Modules\Supplier\Repository\ServiceRepository;

	class ServiceRepositoryTest extends TestCase {

		use DatabaseTransactions;

		function test_get_all_service_with_product(){

			$repository = new ServiceRepository();

			$allService = $repository->allService();

			$this->assertEquals(true, !$allService->isEmpty());

			$this->assertEquals(3, $allService->count() );

			foreach($allService as $key => $service){
				$aService = $allService->get($key)->toArray();
				$this->assertArrayHasKey('products',$aService);
				$this->assertGreaterThan(0, $aService['products']);
			}
		}

		function test_get_service_by_class_name(){

			$repository = new ServiceRepository();

			$service = $repository->getByClass('fuel');

			$this->assertEquals('fuel', $service->class);
		}
	}