<?php namespace Modules\Supplier;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {
	protected $table = "suppliers";

	protected $uploadPath = "";

	protected $fillable = ['company_name', 'first_name', 'last_name', 'company_name', 'email_address', 'phone', 'road', 'postal_code', 'country_id', ];

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
		return ($onlyPath) ? "uploads/" . $name : public_path('uploads/' . $name);
	}
}