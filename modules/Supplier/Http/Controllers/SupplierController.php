<?php namespace Modules\Supplier\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Modules\Supplier\Http\Requests\SupplierImageUpload;
use Modules\Supplier\Http\Requests\SupplierRequest;
use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Repository\SupplierRepository;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SupplierController extends Controller {

	private $supplierRepository;

	public function __construct( SupplierRepository $supplierRepository)
	{
		$this->supplierRepository = $supplierRepository;
	}

	public function search( Request $request ){

		$searchBuilder = new SearchBuilder( $request->all() );
		
		if( $request->has('service') ){
			$searchBuilder->add( new ByService() );
		}

		if( $request->has('products') ){
			$searchBuilder->add( new ByProducts );
		}

		return $searchBuilder->getResults();
	}

	public function postSupplier(SupplierRequest $request){
		return $this->supplierRepository->store($request->all());
	}

	public function putSupplier(SupplierRequest $request, $supplier){
		return $this->supplierRepository->store($request->all(), $supplier->id);
	}

	public function uploadProfile(SupplierImageUpload $request){

		$file = $request->file('image');

		$extension = $file->getClientOriginalExtension();

		Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
	}

	public function resizeImage($file) {

		$types = array('.','-thumbnail.', '-resiged.');

		$sizes = array( array('60', '60'), array('200', '200') );

		$fname = $file->getClientOriginalName();

		$ext = $file->getClientOriginalExtension();

		$nameWithOutExt = str_random(9);

		$original = $nameWithOutExt . array_shift($types) . $ext;

		Storage::disk( 'local' )->put( $original, File::get( $file ) );

//		foreach()
	}
}