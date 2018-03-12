<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\Auth\Login as AuthLoginRequest;
use App\Events\Auth\Login as AuthLoginEvent;

class AuthController extends Controller
{
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
    *   @SWG\Response(response=200, description="successful operation")
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
}
