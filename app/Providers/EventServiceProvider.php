<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\User\Login' => [
            'App\Listeners\User\LoginListener',
        ],
        'App\Events\User\Register' => [
            'App\Listeners\User\RegisterListener',
        ],
        'App\Events\User\RegisterActivate' => [
            'App\Listeners\User\RegisterActivateListener',
        ],
        'App\Events\User\Activate' => [
            'App\Listeners\User\ActivateListener',
        ],
        'App\Events\User\ForgotPassword' => [
            'App\Listeners\User\ForgotPasswordListener',
        ],
        'App\Events\User\ChangePassword' => [
            'App\Listeners\User\ChangePasswordListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
