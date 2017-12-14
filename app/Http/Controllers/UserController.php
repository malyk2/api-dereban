<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use App\Events\User\Login as UserLoginEvent;
use App\Events\User\Register as UserRegisterEvent;
use App\Events\User\RegisterActivate as UserRegisterActivateEvent;
use App\Events\User\Activate as UserActivateEvent;
use App\Http\Requests\User\Register as UserRegisterRequest;
use App\Http\Requests\User\RegisterActivate as UserRegisterActivateRequest;
use App\Http\Requests\User\Login as UserLoginRequest;
use App\Http\Requests\User\Activate as UserActivateRequest;


class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $data = $request->only('email', 'password');
        $data['name'] = explode('@', $data['email'])[0];
        $data['lang'] = app()->getLocale();
        $data['password'] = bcrypt($data['password']);
        $data['status'] = User::STATUS_ACTIVE;
        $user = User::create($data);
        $token = $user->createToken('access_token')->accessToken;

        event(new UserRegisterEvent($user));
        
        return response()->success(compact('token', 'user'), 'User created', 201);
    }

    public function registerActivate(UserRegisterActivateRequest $request) {
        $data = $request->only('email', 'password', 'url');
        $data['name'] = explode('@', $data['email'])[0];
        $data['lang'] = app()->getLocale();
        $data['password'] = bcrypt($data['password']);
        $data['status'] = User::STATUS_NEW;
        $user = User::create($data);

        $activateLink = str_replace_first('{hash}', md5($user->id.$user->created_at), $data['url']);

        event(new UserRegisterActivateEvent($user, $activateLink));

        return response()->success(compact('user'), 'User created. Email to activate account sended.', 201);
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

            event(new UserLoginEvent($user));
            
            return response()->success(compact('token', 'user'), 'Login success', 200);

        } else {
            return response()->error('Unauthorized', 401);
        }
    }

    public function activate(UserActivateRequest $request)
    {
        $data = $request->only('hash');
        $user = User::whereRaw('MD5(CONCAT(id, created_at)) = "'. $data['hash'].'"')->first();
        if ( ! empty($user)) {
            if ($user->status != User::STATUS_NEW) {
                return response()->error("Account hasn't status 'new'", 405);
            }
            $user->status = User::STATUS_ACTIVE;
            $user->save();
            $token = $user->createToken('access_token')->accessToken;

            event(new UserActivateEvent($user));

            return response()->success(compact('token', 'user'), 'Account activated', 200);
        } else {
            return response()->error('Invalid link', 400);
        }
    }

}
