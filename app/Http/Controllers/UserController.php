<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use App\Http\Requests\User\Register as UserRegisterRequest;
use App\Http\Requests\User\Login as UserLoginRequest;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $data = $request->only('email', 'password');
        $data['name'] = explode('@', $data['email'])[0];
        $data['lang'] = app()->getLocale();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $token = $user->createToken('access_token')->accessToken;
        return response()->success(compact('token', 'user'), 'User created', 201);
    }

    public function login(UserLoginRequest $request)
    {
        $data = $request->only('email', 'password');
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();
            $token = $user->createToken('access_token')->accessToken;
            return response()->success(compact('token', 'user'), 'Login success', 200);
        } else {
            return response()->error('Unauthorized', 401);
        }
    }

}
