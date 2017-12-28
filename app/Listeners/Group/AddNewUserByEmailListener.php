<?php

namespace App\Listeners\Group;

use App\Events\Group\AddNewUserByEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddNewUserByEmailListener
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
     * @param  AddNewUserByEmail  $event
     * @return void
     */
    public function handle(AddNewUserByEmail $event)
    {
        
    }
}
