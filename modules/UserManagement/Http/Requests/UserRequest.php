<?php namespace Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {

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
		$emailRule = 'required|unique:users';
		if( $user = $this->route('user') ) {
			$emailRule.= ",id," . $user->id;
		}
		return [
			'email' => $emailRule
		];
	}

}
