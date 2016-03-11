<?php namespace Modules\Threads\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Threads\Entities\Thread;
use Modules\Threads\Policy\ThreadPolicyFactory as PolicyFactory;
use Modules\Threads\Policy\ThreadControl;
use Modules\Threads\Policy\ThreadCreatePolicy;
use Validator;

class ThreadRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$policy = new ThreadCreatePolicy();

		if( $this->route('thread') )
		{
			$policy = new ThreadControl();
		}

		$status = PolicyFactory::make( $this, $policy );

		return $status;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$titleRule = ($this->route('thread')) ? "" : "required|";

		$descriptionRule = ($this->route('thread')) ? "" : "required";

		return [
			'title' => $titleRule . 'max:255',
			'description' => $descriptionRule,
			'status' => "in:" . implode(',' ,array_keys(Thread::$status) ),
			'type' => 'in:' . implode(',',array_keys(Thread::$type))
		];
	}

}
