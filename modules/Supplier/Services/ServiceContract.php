<?php namespace Modules\Supplier\Services;

use Illuminate\Http\Request;
use Modules\Supplier\Service;
use Validator;

abstract class ServiceContract{

	protected  $service;

	protected $request;

	protected $repository;

	public function __construct(Service $service){
		$this->service = $service;
	}

	public function buildValidation(){

		Validator::extend('service_products',function($attribute ,$value, $parameter){
		
			if(!is_array($value)) return false;
		
			$ids = $this->service->products->lists('id')->toArray();
		
			$validProducts = array_intersect($value,$ids);
		
			return count($validProducts) !== 0;
		
		},'The products are not valid');

		return array_merge(

			$this->specificValidation(),
			[
				'suppliers_id' => 'required|array|exists:suppliers,id',
				'products' => 'required|array|service_products',

				/*Deleviry addrees*/

				'street' => 'required',
				'postal_code' => 'required',
				'city' => 'required',
				'country' =>'required',

				/*Contact Details*/

				'email_address' => 'required|email',
				'first_name' => 'required',
				'last_name' => 'required',

			]
		);
	}

	/**
	 * Collecting Form Data from contact after the user search
	 * @return [type] [description]
	 */
	
	abstract function specificValidation();

	/**
	 * Getting The Email View
	 * @return [type] [description]
	 */
	abstract function getEmailView();

	/**
	 * Send Emails To Supplier
	 * @return [type] [description]
	 */
	public function sendEmail(){

		$fromEmail = getenv('FROMEMAIL');

        $fromName = getenv('FROMNAME');  
		
		$suppliers = $this->request->get('suppliers');
		
		$suppliers = $this->repository->getSuppliers( $suppliers );

		if( $suppliers->isEmpty() ) return;

		$serviceData = $this->collectFormData();

		$viewData['requirement'] = $service_data;

		$viewData['other_info'] = $this->request->all(); 

		foreach ($suppliers as $supplier) {
			
			$toEmail = $supplier->email;

			Mail::send( $this->getEmailView(), $viewData , function( $m ) use( $supplier,$fromEmail,$fromName ){
				$m->from( $fromEmail, $fromName );
				$m->to( $supplier->email,$supplier->first_name );
			});
		}
	}
}
