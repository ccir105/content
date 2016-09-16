<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class ChangeInfoRequest extends Request
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
        $rules = [
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,'id',
            'name' => 'required',
            'password' => 'required|min:6',
            'mixing' => 'required|in:1,2,3',
            'background' => 'required|in:0,1'
        ];

       $key = $this->route('settingKey');

       $rules = $rules[$key];

       return [$key => $rules];
    }
}
