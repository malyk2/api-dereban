<?php

namespace App\Http\Controllers;

use Auth;

use App\User;


use App\Events\User\Register as UserRegisterEvent;
use App\Events\User\RegisterActivate as UserRegisterActivateEvent;
use App\Events\User\Activate as UserActivateEvent;
use App\Events\User\ForgotPassword as UserForgotPasswordEvent;
use App\Events\User\ChangePassword as UserChangePasswordEvent;
use App\Events\User\СhangeLang as UserСhangeLangEvent;

use App\Http\Requests\User\Register as UserRegisterRequest;
use App\Http\Requests\User\RegisterActivate as UserRegisterActivateRequest;
use App\Http\Requests\User\Activate as UserActivateRequest;
use App\Http\Requests\User\ForgotPassword as UserForgotPasswordRequest;
use App\Http\Requests\User\ChangePassword as UserChangePasswordRequest;
use App\Http\Requests\User\ChangeLang as UserChangeLangRequest;
use App\Http\Requests\User\CheckExistsByEmail as CheckExistsByEmailRequest;

use App\Http\Resources\User\InviteInfo as UserInviteInfoResourse;

class UserController extends Controller
{
    /**
    * @SWG\Post(
    *   path="/user/register",
    *   summary="Register user",
    *   operationId="getCustomerRates",
    *   tags={"users"},
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

    public function changeLang(UserChangeLangRequest $request)
    {
        $data = $request->only('lang');
        $user = Auth::user();
        $user->lang = $data['lang'];
        app()->setLocale($data['lang']);
        $user->save();

        event(new UserСhangeLangEvent($user));

        return response()->success([], 'Language changed', 200);
    }

    /**
    * @SWG\Get(
    *   path="/user/getAuthUserInfo",
    *   summary="Get info about auth user",
    *   tags={"users"},
    *   security={
    *     {"passport": {}},
    *   },
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
    */
    public function getAuthUserInfo()
    {
        return response()->success(['user' => Auth::user()]);
    }

    public function checkExistsByEmail(CheckExistsByEmailRequest $request)
    {
        $data = $request->only('email');
        $exists = User::where('email', $data['email'])->first();
        $message = $exists ? 'User founded' : 'User not found';
        $user = ! empty($exists) ? new UserInviteInfoResourse($exists) : false;

        return response()->success(compact('user'), $message, 200);
    }

}
