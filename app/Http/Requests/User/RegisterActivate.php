<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiRequest;

class RegisterActivate extends ApiRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'url' => 'required|url|urlHasHash'
        ];
    }
    
}
