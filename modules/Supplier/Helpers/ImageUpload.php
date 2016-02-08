<?php namespace Modules\Supplier\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Modules\Supplier\Supplier;
	
	class ImageUpload {

		static function getTypes(){
			return array('.','-thumbnail.', '-resized.');
		}

		/**
		 * Process image, make thumbnail and resized images
		 * @param  [type] $file [description]
		 * @return [type]       [description]
		 */
		static function processImage( $file ) {
			$types = self::getTypes();

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

			return  $nameWithOutExt . '.' .$ext;
		}

		static function renameImage( $title, $image ){
			
			list( $name, $ext ) = explode('.', $image);
			
			$newName = str_slug( $title ) . '-' . str_random(5);
			
			foreach (self::getTypes() as $type) {

				$oldImage = $name . $type . $ext;

				$newImage = $newName . $type . $ext;
				
				Storage::disk('local')->move( $oldImage, $newImage );
			}

			return $newName .'.'. $ext;
		}
	}