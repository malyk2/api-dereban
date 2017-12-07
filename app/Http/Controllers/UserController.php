<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function test()
    {
        $user = \App\User::find(1);
        return response()->success(compact('user'), 'test_mesage');
    }
    
    public function create(\App\Http\Requests\UserCreate $request)
    //public function create(Request $request)
    {
        $vars = ['email', 'password'];
        $rules = [
            'email' => 'required|email',
            'password' => 'required|email'
        ];
        $data = $request->apiValidate($vars, $rules);

        dd($data);
        
    }

}
