<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use App\Http\Requests\ApiRequest;

class ForgotPassword extends ApiRequest
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
            'email' => [
                'required',
                'email',
                Rule::exists('users')->where(function ($q) {
                    $q->where('status', \App\User::STATUS_ACTIVE);
                }),
            ],
            'url' => 'required|url|urlHasHash'
        ];
    }
    
}
