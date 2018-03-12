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
        'App\Events\Auth\Login' => [
            'App\Listeners\Auth\LoginListener',
        ],
        'App\Events\Auth\Register' => [
            'App\Listeners\Auth\RegisterListener',
        ],
        'App\Events\Auth\RegisterActivate' => [
            'App\Listeners\Auth\RegisterActivateListener',
        ],
        'App\Events\Auth\Activate' => [
            'App\Listeners\Auth\ActivateListener',
        ],
        'App\Events\Auth\ForgotPassword' => [
            'App\Listeners\Auth\ForgotPasswordListener',
        ],
        'App\Events\Auth\ChangePassword' => [
            'App\Listeners\Auth\ChangePasswordListener',
        ],
        'App\Events\User\СhangeLang' => [
            'App\Listeners\User\СhangeLangListener',
        ],
        'App\Events\Group\Сreate' => [
            'App\Listeners\Group\СreateListener',
        ],
        'App\Events\Group\Update' => [
            'App\Listeners\Group\UpdateListener',
        ],
        'App\Events\Group\Delete' => [
            'App\Listeners\Group\DeleteListener',
        ],
        'App\Events\Group\AddUser' => [
            'App\Listeners\Group\AddUserListener',
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
