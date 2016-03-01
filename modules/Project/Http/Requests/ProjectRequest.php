<?php namespace Modules\Project\Http\Requests;
use Modules\Project\Repositories\RepositoryFactory;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */

	protected $model;

	public function __construct(){
		$this->model = RepositoryFactory::make();
	}

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
		$rules = [];
		if( method_exists( $this->model, 'getRules' ) ){
			$rules = $this->model->getRules($this);
		}
		return array_merge($rules,[
			'title' => 'required',
			'description' => 'required'
		]);
	}

}
