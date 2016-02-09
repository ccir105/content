<?php namespace Modules\Supplier\Services;

use Illuminate\Http\Request;
use Modules\Supplier\Service;
use Modules\Supplier\Product;
use Modules\Supplier\Supplier;
use Validator;

abstract class ServiceContract{

	protected  $service;

	protected $request;

	protected $repository;

	public function __construct(Service $service){
		$this->service = $service;
	}

	public function setRequest($request){
		$this->request = $request;
	}

	public function buildValidation(){

		Validator::extend('service_products',function($attribute ,$value, $parameter){
			return !is_null( Product::find($value) );
		},'The products are not valid');

		return array_merge(

			$this->specificValidation(),
			[
				'suppliers_id' => 'required|array|exists:suppliers,id',
				'product' => 'required|service_products',

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
	abstract function emailDataFormat();

	/**
	 * Send Emails To Supplier
	 * @return [type] [description]
	 */
	public function sendEmail(){

		$fromEmail = getenv('FROMEMAIL');

        $fromName = getenv('FROMNAME');  
		
		$suppliers = $this->request->get('suppliers_id');
		
		$suppliers = Supplier::whereIn('id',$suppliers)->get();

		if( $suppliers->isEmpty() ) return;

		$emailData = $this->makeEmailData();

		foreach ($suppliers as $supplier) {

			Mail::send( 'email' , $emailData , function( $m ) use( $supplier, $fromEmail,$fromName ){
				$m->from( $fromEmail, $fromName );
				$m->to( $supplier->email, $supplier->first_name );
			});
		}
	}

	public function makeEmailData(){

		$serviceDataKey = $this->emailDataFormat();

		$productData = [];
		$contactData = [];

		foreach( $serviceDataKey as $key => $name ){
			if( $this->request->has($key) ){
				$productData[$name] = $this->request->get($key);
			}
		}

		$contactData['product'] = $productData;

		$contactData['product']['Produkt'] = Product::find($this->request->get('product'))->name;

		$userDetailsData = array(
			'email_address' => 'envelop',
			'name' => 'person',
			'phone' => 'phone'
		);

		$deliveryData = [
			'street' => 'Straße',
			'country' => 'Land',
			'postal_code'=>'Postleitzahl',
			'city'=> 'Ort'
		];



		foreach( $userDetailsData as $key => $icon ){
			if($key == 'name'){
				$firstName = $this->request->get('first_name');
				$lastName = $this->request->get('last_name');
				$contactData['user_detail'][$icon] = $firstName . ' ' . $lastName;
				continue;
			}

			if( $this->request->has($key) ){
				$contactData['user_detail'][$icon] = $this->request->get($key);
			}
		}

		foreach( $deliveryData as $key => $name ){
			if( $this->request->get( $key ) ){
				$contactData['delivery'][$name] = $this->request->get($key);
			}
		}

		return $contactData;
	}

}
