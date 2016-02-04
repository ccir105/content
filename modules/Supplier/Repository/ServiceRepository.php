<?php namespace Modules\Supplier\Repository;
	use Modules\Supplier\Service;

	class ServiceRepository {

		private $model;

		public function __construct()
		{
			$this->model = new Service;
		}

		/**
		 * Get All Service with Product For the drop down
		 * @return [type] [description]
		 */
		
		public function allService()
		{
			return $this->model->with('products')->get();
		}

		/**
		 * Get all the suppliers by service slug or id
		 * @param  [type] $slug [description]
		 * @return [type]       [description]
		 */
		
		public function suppliersByService( $service )
		{
			return $this->serviceGetter($service)->with( 'suppliers', 'suppliers.profile' )->paginate(10);
		}

		/**
		 * @param $service
		 * @return mixed
		 */
		public function getProducts($serviceClass){
			if( $service = $this->getByClass( $serviceClass ) ){
				return $service->products;
			}
			return [];
		}

		/**
		 * Resolve service getter either slug or by id
		 * @return [type] [description]
		 */

		public function serviceGetter($service)
		{
			return is_numeric( $service ) ? Service::where('id','=',$service) : Service::whereSlug($service);
		}


		/**
		 * @param $name
		 * @return mixed
		 */
		public function getByClass($name)
		{
			return $this->model->where('class', '=', $name)->first();
		}

		public function getByService($service){
			$service = $this->serviceGetter($service)->first();
			if( $service ){
				$suppliers = $service->suppliers;
				if(!$suppliers->isEmpty()){
					return $suppliers->lists('id')->toArray();
				}
			}
		}
	}