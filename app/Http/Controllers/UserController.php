<?php

namespace App\Http\Controllers;

use Auth;

use App\User;

use App\Events\User\СhangeLang as UserСhangeLangEvent;

use App\Http\Requests\User\ChangeLang as UserChangeLangRequest;
use App\Http\Requests\User\CheckExistsByEmail as CheckExistsByEmailRequest;

use App\Http\Resources\User\InviteInfo as UserInviteInfoResourse;

class UserController extends Controller
{
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
