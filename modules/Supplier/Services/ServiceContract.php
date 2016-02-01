<?php namespace Modules\Supplier\Services;

use Illuminate\Http\Request;
use Modules\Supplier\Service;
use Modules\Repository\SupplierRepository;

abstract class ServiceContract  {

	protected  $service;

	protected $request;

	protected $repository;

	public function __construct(Service $service, Request $request,SupplierRepository $repository){
		$this->service = $service;
		$this->request = $request;
		$this->repository = $repository;
	}

	/**
	 * Collecting Form Data from contact after the user search
	 * @return [type] [description]
	 */
	
	abstract function collectFormData();

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
