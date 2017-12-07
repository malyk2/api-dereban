<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
//use Illuminate\Foundation\Http\FormRequest;

class UserCreate extends FormRequest
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
            'email' => 'required|email',
            'email2' => 'required|email'
        ];
    }

//    public function failedValidation(Validator $validator)
//    {
//        dd('usercr');
//    }

    
}
