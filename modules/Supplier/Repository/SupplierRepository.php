<?php namespace Modules\Supplier\Repository;
	use Modules\Supplier\Entities\SupplierCountry;
	use Modules\Supplier\Supplier;
	use Modules\Supplier\Product;
	use Modules\Supplier\Helpers\ImageUpload as ImageHelper;
	
	class SupplierRepository {
		
		public $supplier;

		const PAGINATION = 5;

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

			if( isset($profileData['profile_image'] ) ){

				$filePath = $this->supplier->getUploadPath( $profileData['profile_image'] );

				if( !file_exists( $filePath ) ){
					unset( $profileData['profile_image'] );
				} else{
					$profileData['profile_image'] = ImageHelper::renameImage($inputs['company_name'], $profileData['profile_image']);
				}
			}

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

			if( isset( $inputs['products'] ) ) {
				$this->saveService($supplier, $inputs['products']);
			}

			if( isset( $inputs['country'] ) ){
				if(is_null($id) ){
					$this->saveCountry($inputs['country'],$supplier);
				}else{
					$this->editCountry($inputs['country'],$supplier);
				}
			}

			return $supplier->load('profile');
		}

		/**
		 * @return \Illuminate\Database\Eloquent\Collection|static[]
		 */
		public function all(){
			return $this->supplier->with('profile')->latest()->get();
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

			$products->each(function($product)use ( &$finalArray ) {
				$finalArray[ $product->id ] = ['service_id'=> $product->service_id ];
			});

			return $finalArray;
		}

		public function findByQueryString($query, $column = 'company_name'){

			if( is_array($column ) ) {
				$suppliersBuilder = $this->supplier->whereRaw("MATCH(" . implode(',', $column) . ") AGAINST ('$query' IN NATURAL LANGUAGE MODE)"); //multi match quwery
			}else{
				$suppliersBuilder = $this->supplier->where($column,'LIKE',"$query%");
			}
			return $suppliersBuilder->paginate(self::PAGINATION);
		}

		public function findByCountry( $countryId, $supplierIds = null ,$zipCode = null){
			
			$query = $this->fresh()->from('supplier_country')->where('country_id','=',$countryId);

			if(!is_null( $supplierIds ) ){
				$query->whereIn( 'supplier_id', $supplierIds);
			}

			if(!is_null($zipCode)){
				$query->whereRaw('? between zip_from and zip_to',[$zipCode]);
			}

			$result = $query->get();

			if(!$result->isEmpty() ){
				return $result->lists('supplier_id')->toArray();
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

		public function delete($supplier){
			return $supplier->delete();
		}

		public function searchByQuery($query){
			$data = $this->findByQueryString($query);
			return $data;
		}

		public function saveCountry( $countries, $supplier ){

			$countryArr = [];

			foreach($countries as $country){
				foreach($country['zip_code'] as $zip) {
					$insArr = array_merge($zip, ['supplier_id'=>$supplier->id,'country_id' => $country['id']]);
					$countryArr[] = with(new SupplierCountry($insArr))->save();
				}
			}

			return true;
		}

		public function editCountry($countries, $supplier){
			SupplierCountry::where('supplier_id','=',$supplier->id)->delete();
			return $this->saveCountry($countries, $supplier);
		}

		public function editActivation($value, $supplier){
			$supplier->status = $value;
			return $supplier->save();
		}

		public function getLatest(){
			return $this->supplier->latest()->take(10)->get();
		}
	}