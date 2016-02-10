<?php namespace Modules\Supplier\Entities;
   
use Illuminate\Database\Eloquent\Model;

class SupplierCountry extends Model {

    protected $fillable = ['country_id','supplier_id','zip_from','zip_to'];

    protected $table = 'supplier_country';


}