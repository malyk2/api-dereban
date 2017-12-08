<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class ResponseApiServiceProvider extends ServiceProvider

{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data, $message = '', $status = 200) {
            return Response::json([
              'message'  => $message,
              'data' => $data,
            ], $status);
        });

        Response::macro('error', function ($message = '', $status = 400) {
            return Response::json([
              'data'  => false,
              'message' => $message,
            ], $status);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
