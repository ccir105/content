<?php namespace Modules\Supplier;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
	protected $table = "products";

	public function service(){
		return $this->belongsTo('Modules\Service','service_id','id');
	}
}