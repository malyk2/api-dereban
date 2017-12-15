<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiRequest;

class ChangePassword extends ApiRequest
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
        return [
            'password' => 'required|min:6|confirmed',
            'hash' => 'required'
        ];
    }
    
}
