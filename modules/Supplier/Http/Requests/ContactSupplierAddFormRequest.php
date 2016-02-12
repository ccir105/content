<?php namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactSupplierAddFormRequest extends FormRequest {

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
		return [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email',
			'street' => 'required',
			'postal_code' => 'required',
			'city' => 'required',
			'country' => 'required',
			'description' => 'required',
		];
	}

}
