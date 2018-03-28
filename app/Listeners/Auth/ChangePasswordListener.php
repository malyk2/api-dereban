<?php

namespace App\Listeners\Auth;

use App\Events\Auth\ChangePassword;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangePasswordListener
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
     * @param  ChangePassword  $event
     * @return void
     */
    public function handle(ChangePassword $event)
    {

    }
}
