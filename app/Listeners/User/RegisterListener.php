<?php

namespace App\Listeners\User;

use App\Mail\UserCreate as MailUserCreate;
use App\Events\User\Register;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterListener
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
     * @param  Register  $event
     * @return void
     */
    public function handle(Register $event)
    {
        $user = $event->user;

        Mail::to($user->email)->send(new MailUserCreate($user));
    }
}
