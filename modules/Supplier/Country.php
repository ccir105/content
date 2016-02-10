<?php namespace Modules\Supplier;
   
use Illuminate\Database\Eloquent\Model;

class Country extends Model {

	protected $table = "countries";

    protected $fillable = ['name'];

    public function supplier(){
        return $this->belongsToMany('Modules\Supplier\Supplier','supplier_country','country_id','supplier_id');
    }
}