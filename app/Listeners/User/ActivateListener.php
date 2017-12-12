<?php

namespace App\Listeners\User;

use App\Events\User\Activate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateListener
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
     * @param  Activate  $event
     * @return void
     */
    public function handle(Activate $event)
    {
        
    }
}
