<?php

namespace App\Listeners\Group;

use App\Events\Group\AddUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUserListener
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
     * @param  AddUser  $event
     * @return void
     */
    public function handle(AddUser $event)
    {
        //
    }
}
