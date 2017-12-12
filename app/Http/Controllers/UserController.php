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
        $data['status'] = 1;
        $user = User::create($data);
        $token = $user->createToken('access_token')->accessToken;
        return response()->success(compact('token', 'user'), 'User created', 201);
    }

    public function login(UserLoginRequest $request)
    {
        $data = $request->only('email', 'password');
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();
            if ($user->status != 1) {
                return response()->error('Account not activated', 401);
            }
            $token = $user->createToken('access_token')->accessToken;
            return response()->success(compact('token', 'user'), 'Login success', 200);
        } else {
            return response()->error('Unauthorized', 401);
        }
    }

}
