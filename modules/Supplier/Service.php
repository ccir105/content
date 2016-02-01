<?php namespace Modules\Supplier;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Service extends Model implements SluggableInterface{
	protected $table = "services";
	use SluggableTrait;

	 protected $sluggable = [
	        'build_from' => 'name',
	        'save_to'    => 'slug',
	        'on_update' => false
	];

	public function products(){
		return $this->hasMany('Modules\Supplier\Product','service_id','id');
	}

	public function suppliers(){
		return $this->belongsToMany('Modules\Supplier\Supplier','supplier_products','service_id')->groupBy('supplier_id');
	}
}