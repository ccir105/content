<?php namespace Modules\Supplier\Repository;
	
	use Modules\Supplier\Service;
	use Modules\Supplier\Supplier;

	class AdminRepository {
		
		private $supplier;

		public function __construct(Supplier $supplier){
			$this->supplier = $supplier;
		}

		/**
		 * Handles the adding and editing of suppliers form admin
		 * @param  array  $input [description]
		 * @param  [type] $id    [description]
		 * @return [type]        [description]
		 */
		
		public function dataOperation( $input = array() , $id = null ){

		}

		public function serchSupplier( $name = "" ){
			
		}
	}