<?php namespace Modules\Project\Http\Requests;
use Modules\Project\Repositories\RepositoryFactory;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */

	protected $repo;

	public function __construct(){
		$this->repo = RepositoryFactory::make();
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
		$instance = $this->repo->getInstance();
		if( method_exists( $instance, 'getRules' ) ){
			$rules = $instance->getRules($this);
		}
		return array_merge($rules,[
			'title' => 'required',
			'description' => 'required'
		]);
	}

}
