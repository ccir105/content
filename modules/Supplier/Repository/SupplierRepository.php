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

			if( isset($inputs['products'])) {
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

		/**
		 * @param $products
		 * @param $supplierIds
		 * @return array|bool
		 */

		public function findByProductBySuppliers( $products, $supplierIds ){

			$query = $this->supplier->newQueryWithoutScopes();

			$suppliersBuilder = $query->from('supplier_products')->whereIn('supplier_id',$supplierIds)->whereIn('product_id', $products)->select('supplier_id');

			return $this->resolveIds($suppliersBuilder);
		}

		public function findByQueryString($query, $column = 'company_name'){

			if( is_array($column ) ) {
				$suppliersBuilder = $this->supplier->whereRaw("MATCH(" . implode(',', $column) . ") AGAINST ('$query' IN NATURAL LANGUAGE MODE)"); //multi match quwery
			}else{
				$suppliersBuilder = $this->supplier->where($column,'LIKE',"$query%");
			}
			return $suppliersBuilder->get();
		}


		public function findByColumn( $inputs, $supplierIds = array() ){

			foreach($this->validSearchColumn() as $name){
				if( isset( $inputs[ $name ] ) ){
					$this->supplier->where( $name, '=' ,$inputs[ $name ] );
					$this->validSearchByColumn = true;
				}
			}

			if( $this->validSearchByColumn ) {
				if( !empty( $supplierIds ) ){
					$this->supplier->whereIn('id',$supplierIds);
				}
				return $this->resolveIds( $this->supplier ,"id");
			}

			return false;
		}

		public function validSearchColumn(){
			return ['country_id'];
		}

		public function resolveIds($suppliersBuilder,$columnListing = "supplier_id"){
			$suppliersCollection = $suppliersBuilder->get();
			if( !$suppliersCollection->isEmpty() ){
				$ids = $suppliersCollection->lists($columnListing)->toArray();
				return array_unique($ids);
			}

			return false;
		}

		public function fresh(){
			return $this->supplier->newQueryWithoutScopes();
		}

		public function searchByProducts( $products = array() ){

			/**
			 * Getting the empty query builder
			 * @var [type]
			 */
			$query = $this->supplier->newQueryWithoutScopes();

			/**
			 * Serching Suppliers
			 * @var [type]
			 */
			$suppliers = $query->from('supplier_products')
				->whereIn( 'product_id', $products)
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