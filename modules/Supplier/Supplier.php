<?php namespace Modules\Supplier;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {
	protected $table = "suppliers";

	protected $uploadPath = "uploads/";

	protected $fillable = ['company_name', 'first_name', 'last_name', 'place', 'email_address', 'phone', 'road', 'postal_code'];

	protected $appends = ['image'];

	public function profile(){
		return $this->hasOne('Modules\Supplier\Profile','supplier_id','id');
	}

	public function services(){
		return $this->belongsToMany('Modules\Supplier\Service','supplier_products','supplier_id')->groupBy('service_id');
	}

	public function products(){
		return $this->belongsToMany('Modules\Supplier\Product','supplier_products','supplier_id','product_id');
	}

	public static function getUploadPath($name = "",$onlyPath = null){
		return ( $onlyPath ) ? "uploads/" . $name : public_path('uploads/' . $name);
	}

	public function getImageAttribute(){
		if( !isset( $this->profile ) ){
			$this->load('profile');
		}
		 
		$imagePath = empty($this->profile->profile_image) ? "default.png" : $this->profile->profile_image;
		return asset( $this->getUploadPath( $imagePath , true) );
	}

	public function country(){
		return $this->belongsToMany('Modules\Supplier\Country','supplier_country','supplier_id','country_id')->withPivot(['zip_from','zip_to']);
	}
}