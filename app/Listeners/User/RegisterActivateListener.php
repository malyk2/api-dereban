<?php

namespace App\Listeners\User;

use App\Mail\UserActivate as MailUserActivate;
use Illuminate\Support\Facades\Mail;
use App\Events\User\RegisterActivate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterActivateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RegisterActivate  $event
     * @return void
     */
    public function handle(RegisterActivate $event)
    {
        $user = $event->user;
        $user->activate_link = $event->activateLink;

        Mail::to($user->email)->send(new MailUserActivate($user));
    }
}
