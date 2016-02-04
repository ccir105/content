<?php namespace Modules\Supplier\Repository;
	use Modules\Supplier\Supplier;
	use Modules\Supplier\Product;

	class SupplierRepository {
		
		public $supplier;

		private $validSearchByColumn = false; //just a flag

		public function __construct(){
			$this->supplier = new Supplier;
		}

		/**
		 * Get the supplier list by ids
		 * @param  array  $ids [description]
		 * @return [type]      [description]
		 */
		public function getSuppliers( $ids = array() ){
			return $this->supplier->whereIn('id',$ids)->get();	
		}

		/**
		 * @param $array
		 * @return bool
		 */
		public function store($inputs = [], $id = null)
		{
			$supplier = $this->supplier;

			if ( !is_null( $id ) ){
				$supplier = $this->supplier->find( $id );
				if(is_null($supplier)) return false;
			}

			$profileData = $inputs['profile'];

			unset($inputs['profile']);

			$supplier->fill( $inputs );

			$supplier->save();

			if(is_null($supplier->profile))
			{
				$supplier->profile()->create( $profileData );

			} else {
				$profile = $supplier->profile;
				$profile->fill($profileData);
				$profile->save();
			}

			if( isset($inputs['products'] ) ) {
				$this->saveService($supplier, $inputs['products']);
			}

			return $supplier;

		}

		/**
		 * @return \Illuminate\Database\Eloquent\Collection|static[]
		 */
		public function all(){
			return $this->supplier->with('profile')->get();
		}

		/**
		 * @param $supplier
		 * @param array $products
		 */
		public function saveService( $supplier, $products = array() )
		{
			$supplier->products()->sync($this->productListWithService($products));
		}

		/**
		 * @param array $products
		 * @return array
		 */

		public function productListWithService($products = array()){
			$products = Product::whereIn('id',$products)->select('service_id','id')->get();
			$finalArray = [];

			$products->each(function($product)use (&$finalArray) {
				$finalArray[$product->id] = ['service_id'=> $product->service_id ];
			});

			return $finalArray;
		}

		public function findByQueryString($query, $column = 'company_name'){

			if( is_array($column ) ) {
				$suppliersBuilder = $this->supplier->whereRaw("MATCH(" . implode(',', $column) . ") AGAINST ('$query' IN NATURAL LANGUAGE MODE)"); //multi match quwery
			}else{
				$suppliersBuilder = $this->supplier->where($column,'LIKE',"$query%");
			}
			return $suppliersBuilder->get();
		}

		public function findByCountry( $countryId, $supplierIds = null ){
			
			$query = $this->fresh()->from('suppliers');

			if(!is_null( $supplierIds ) ){
				$query->whereIn( 'id', $supplierIds);
			}

			$result = $query->where('country_id','=',$countryId)->get();

			if(!$result->isEmpty()){
				return $result->lists('id')->toArray();
			}
		}

		public function fresh(){
			return $this->supplier->newQueryWithoutScopes();
		}

		public function searchByProducts( $products = array(), $supplierIds = null ){


			/**
			 * Getting the empty query builder
			 * @var [type]
			 */
			$query = $this->fresh();

			/**
			 * Serching Suppliers
			 * @var [type]
			 */

			$query->from('supplier_products');

			if(!is_null($supplierIds) ){
				$query->whereIn('supplier_id',$supplierIds);
			}

			$suppliers = $query->whereIn( 'product_id', $products)
				->get();

			if( !$suppliers->isEmpty() ){

				$suppliers = $suppliers->lists('supplier_id')->toArray();
				/**
				 * Serch Result may have same id of suppliers so better make unique
				 * @var array
				 */

				return array_unique( $suppliers );

			}

			return false;
		}

	}