<?php namespace Modules\Supplier\Http\Controllers;

use Modules\Supplier\Http\Requests\ContactFormRequest;
use Modules\Supplier\Http\Requests\ContactSupplierAddFormRequest;
use Modules\Supplier\Http\Requests\SupplierImageUpload;
use Modules\Supplier\Http\Requests\SupplierRequest;
use Modules\Supplier\Repository\SupplierRepository;
use Modules\Supplier\Supplier;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Modules\Supplier\Helpers\ImageUpload as ImageHelper;
use Modules\Supplier\Scope\EmailTrait;

class SupplierController extends Controller {

	use EmailTrait;

	private $supplierRepository;

	public function __construct( SupplierRepository $supplierRepository )
	{
		$this->supplierRepository = $supplierRepository;
	}

	public function postSupplier( SupplierRequest $request ){
		return $this->supplierRepository->store( $request->all() );
	}

	public function putSupplier( SupplierRequest $request, $supplier ){

		return $this->supplierRepository->store( $request->all(), $supplier->id );
	}

	public function uploadProfile(SupplierImageUpload $request){

		$file = $request->file('image');
		return $this->resizeImage( $file );
	}

	private function resizeImage( $file ) {
		
		$imageName = ImageHelper::processImage( $file );
		
		return [
			'short_name' => $imageName, 
			'url' => url( Supplier::getUploadPath( $imageName , true ) )
		];
	}

	public function delete(Supplier $supplier){
		return [ 'status' => $supplier->delete() ];
	}

	public function getAll(){
		return $this->supplierRepository->all();
	}

	public function searchByQuery(Request $request){
		return $this->supplierRepository->searchByQuery($request->get('query'));
	}

	public function getSupplier($supplier){
		return $supplier->load('profile','country','products');
	}

	public function activate($supplier){
		return ['status' => $this->supplierRepository->editActivation(1, $supplier)];
	}

	public function deactivate($supplier){
		return ['status' => $this->supplierRepository->editActivation(0, $supplier)];
	}

	public function addSupplierContactEmail(ContactSupplierAddFormRequest $request){	
		return ['status' => $this->sendNewSupplierContactEmail($request)];
	}

	public function contactFormEmail(ContactFormRequest $request){
		return ['status' => $this->sendContactEmail($request)];
	}

	public function getLatestSupplier(){
		return $this->supplierRepository->getLatest();
	}
}