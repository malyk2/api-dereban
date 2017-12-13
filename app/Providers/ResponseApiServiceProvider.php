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
            $message = __($message);
            $errors = request()->errors();
            $response = compact('data', 'message', 'errors');
            return Response::json($response ,$status);
        });

        Response::macro('error', function ($message = '', $status = 400, $validate = null) {
            $message = __($message);
            $errors = request()->errors();
            $response = compact('message', 'validate', 'errors');
            return Response::json($response, $status, []);
        });

        Request::macro('addError', function($text = '', $type = 'error'){
            $this->errors = empty($this->errors) ? [] : $this->errors;
            $this->errors = array_merge($this->errors, [__($text) => $type]);
        });

        Request::macro('errors', function() {
            return $this->errors;
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
