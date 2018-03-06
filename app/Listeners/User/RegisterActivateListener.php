<?php

namespace App\Listeners\User;

use App\Events\User\RegisterActivate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendMail;

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
        SendMail::dispatch($user, $user->activate_link)->onQueue('emails');
    }
}
