<?php namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Supplier\Services\ServiceFactory;

class SupplierContactRequest extends FormRequest {

	private $service;

	public function __construct(){
		parent::__construct();
		$this->service = ServiceFactory::getService();
	}

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
		return $this->service->buildValidation();
	}

}
