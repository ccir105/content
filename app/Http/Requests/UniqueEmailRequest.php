<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Validator;
class UniqueEmailRequest extends Request
{
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
        Validator::extend('not_unique_email',function($attr, $value, $parameter, $validator){
            return is_null(User::where('email',$value)->first()) ? false : true;
        },'This email is not registered yet');

        return [
            'email' => 'required|email|max:255|not_unique_email'
        ];
    }
}
