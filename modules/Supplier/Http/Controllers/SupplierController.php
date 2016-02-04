<?php namespace Modules\Supplier\Http\Controllers;

use Modules\Supplier\Http\Requests\SupplierImageUpload;
use Modules\Supplier\Http\Requests\SupplierRequest;
use Modules\Supplier\Repository\SupplierRepository;
use Modules\Supplier\Supplier;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

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
		return $this->resizeImage( $file );
	}

	public function resizeImage($file) {

		$types = array('.','-thumbnail.', '-resized.');

		$sizes = array( array('60', '60'), array('200', '200') );

		$ext = $file->getClientOriginalExtension();

		$nameWithOutExt = str_random(9);

		$original = $nameWithOutExt . array_shift($types) . $ext;

		Storage::disk( 'local' )->put( $original, File::get( $file ) );

		foreach ($types as $key => $type) {

			$newName = $nameWithOutExt . $type . $ext;

			$targetPath = Supplier::getUploadPath($newName);

			Storage::copy($original, $newName);

			Image::make($targetPath)
				->resize($sizes[$key][0], $sizes[$key][1],function($constraint)
				{
					$constraint->aspectRatio();
				})
				->save($targetPath);
		}

		return url(Supplier::getUploadPath($nameWithOutExt . '.' .$ext,true));//
	}
}