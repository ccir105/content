<?php namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Supplier\Product;
use Modules\Supplier\Country;

class SupplierRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */

	public function rules()
	{
		\Validator::extend('check_product',function($attribute ,$value, $parameter){
			return Product::withIds($value)->count() == count($value);
		}, 'The Products are not valid');

//		\Validator::extend('check_country',function($attribute,$value,$parameter){
//			return
//		});

		return [
			'company_name' => 'required',
			'email_address' => 'required|email',
			'profile' => 'required|array',
			'products' => 'required|array|check_product'
		];
	}

}
