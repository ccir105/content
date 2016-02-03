<?php namespace Modules\Supplier;

use Modules\Supplier\Scope\ScopesTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{

	use ScopesTrait;

	protected $table = "products";

	public function service(){
		return $this->belongsTo('Modules\Service','service_id','id');
	}
}