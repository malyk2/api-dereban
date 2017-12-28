<?php

namespace App\Listeners\Group;

use App\Events\Group\AddRegisteredUserByEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddRegisteredUserByEmailListener
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
     * @param  AddRegisteredUserByEmail  $event
     * @return void
     */
    public function handle(AddRegisteredUserByEmail $event)
    {
        //
    }
}
