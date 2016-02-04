<?php namespace Modules\Supplier;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model{
	
	protected $table = "supplier_profiles";

	protected $primaryKey = 'supplier_id';

	public $timestamps = false;

	protected $fillable = ['company_name','website','email','phone','road','address','zip_code','description','profile_image'];
}