<?php

namespace App\Listeners\Group;

use App\Events\Group\Update;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateListener
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
     * @param  Update  $event
     * @return void
     */
    public function handle(Update $event)
    {
        //
    }
}
