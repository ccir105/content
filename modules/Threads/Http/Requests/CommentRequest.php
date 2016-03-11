<?php namespace Modules\Threads\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Threads\Policy\CommentControl;
use Modules\Threads\Policy\CommentCreatePolicy;
use Modules\Threads\Policy\ThreadPolicyFactory as PolicyFactory;
use Modules\Threads\Policy\ThreadControl;

class CommentRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$commentPolicy = new CommentCreatePolicy();

		if( $this->route('comment') )
		{
			$commentControlPolicy = new CommentControl();

			$commentPolicy->setSuccessor($commentControlPolicy);
		}

		$status = PolicyFactory::make( $this, $commentPolicy );

		return $status;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'text' => 'required'
		];
	}

}
