<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\Create as UserCreateRequest;

class UserController extends Controller
{
    public function test()
    {
        $user = \App\User::find(1);
        return response()->success(compact('user'), 'test_mesage');
    }
    
    public function create(UserCreateRequest $request)
    {
        $data = $request->only('email', 'password');
        $data['name'] = 'name';
        $user = User::create($data);
        $token = $user->createToken('Ext Token')->accessToken;
        return response()->success(compact('token'), 'User created', 201);
    }

}
