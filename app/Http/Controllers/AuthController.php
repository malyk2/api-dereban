<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\Auth\Login as AuthLoginRequest;
use App\Http\Requests\Auth\Register as AuthRegisterRequest;
use App\Http\Requests\Auth\RegisterActivate as AuthRegisterActivateRequest;
use App\Http\Requests\Auth\Activate as AuthActivateRequest;
use App\Http\Requests\Auth\ForgotPassword as AuthForgotPasswordRequest;
use App\Http\Requests\Auth\ChangePassword as AuthChangePasswordRequest;

use App\Events\Auth\Login as AuthLoginEvent;
use App\Events\Auth\Register as AuthRegisterEvent;
use App\Events\Auth\RegisterActivate as AuthRegisterActivateEvent;
use App\Events\Auth\Activate as AuthActivateEvent;
use App\Events\Auth\ForgotPassword as AuthForgotPasswordEvent;
use App\Events\Auth\ChangePassword as AuthChangePasswordEvent;

class AuthController extends Controller
{
    /**
    * @SWG\Definition(
    *   definition="UserToken",
    *   type="string",
    *   example="eyJ0eXAiOiJKV1QiL ...",
    * )
    */
    /**
    * @SWG\Post(
    *   path="/login",
    *   summary="Login",
    *   operationId="asd",
    *   tags={"Auth"},
    *   @SWG\Parameter(
    *     name="email",
    *     in="formData",
    *     description="User email",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="password",
    *     in="formData",
    *     description="User password",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Response(response=200, description="Return user token ", @SWG\Schema(ref="#/definitions/UserToken"),),
    * )
    *
    */
    public function login(AuthLoginRequest $request)
    {
        $data = $request->only('email', 'password');
        if ( ! auth()->attempt($data)) {
            return response()->error('Email or Password is invalid', 400);
        }

        $user = auth()->user();
        if ($user->deleted) {
            return response()->error('Email or Password is invalid', 400);
        }

        if ( ! $user->active) {
            return response()->error('Account is not active', 400);
        }

        $token = $user->createToken('access_token')->accessToken;

        event(new AuthLoginEvent($user));

        return response()->success($token, 'Login success', 200);
    }

    /**
    * @SWG\Post(
    *   path="/register",
    *   summary="Register user",
    *   operationId="getCustomerRates",
    *   tags={"Auth"},
    *   @SWG\Parameter(
    *     name="email",
    *     in="formData",
    *     description="New user email",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="password",
    *     in="formData",
    *     description="New user password",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="lang",
    *     in="formData",
    *     description="New user language",
    *     required=false,
    *     enum={"en", "uk", "ru"},
    *     type="string"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=422, description="validate error"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
    */
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->only('email', 'password');
        $data['name'] = explode('@', $data['email'])[0];
        $data['lang'] = app()->getLocale();
        $data['password'] = bcrypt($data['password']);
        $data['status'] = User::STATUS_ACTIVE;
        $data['active'] = true;
        $user = User::create($data);
        $token = $user->createToken('access_token')->accessToken;

        event(new AuthRegisterEvent($user));

        return response()->success(compact('token', 'user'), 'User created', 201);
    }

    public function registerActivate(UserRegisterActivateRequest $request) {
        $data = $request->only('email', 'password', 'url');
        $data['name'] = explode('@', $data['email'])[0];
        $data['lang'] = app()->getLocale();
        $data['password'] = bcrypt($data['password']);
        $data['status'] = User::STATUS_NEW;
        $data['active'] = false;
        $user = User::create($data);

        $activateLink = str_replace_first('{hash}', md5($user->id.$user->created_at), $data['url']);

        event(new UserRegisterActivateEvent($user, $activateLink));

        return response()->success(compact('user'), 'User created. Email to activate account sended.', 201);
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

    public function forgotPassword(UserForgotPasswordRequest $request)
    {
        $data = $request->only('email', 'url');
        $user = User::where('email', $data['email'])->first();
        $restorePasswordLink = str_replace_first('{hash}', md5($user->email.$user->created_at), $data['url']);

        event(new UserForgotPasswordEvent($user, $restorePasswordLink));

        return response()->success(compact('user'), 'Email for restore password sended', 201);
    }

    public function changePassword(UserChangePasswordRequest $request)
    {
        $data = $request->only('password', 'hash');
        $user = User::whereRaw('MD5(CONCAT(email, created_at)) = "'. $data['hash'].'"')->first();
        if ( ! empty($user)) {
            $user->password = bcrypt($data['password']);
            $user->save();

            event(new UserChangePasswordEvent($user));

            return response()->success(compact('user'), 'Password changed', 200);
        } else {
            return response()->error('Invalid link', 400);
        }
    }
}
