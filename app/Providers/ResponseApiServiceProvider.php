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
        Response::macro('success', function ($data, $message = '') {
            return Response::json([
              'message'  => $message,
              'data' => $data,
            ]);
        });

        Response::macro('error', function ($message = '', $status = 400) {
            return Response::json([
              'data'  => false,
              'message' => $message,
            ], $status);
        });
        
        Request::macro('apiValidate', function ($vars, $rules = [], $messages = [], $customAttributes = []) {
            $data = $this->only($vars);
            $validator = Validator::make($data, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                throw (new \App\Exceptions\ApiValidationException($validator))->withValidator($validator)->withCode(422);
            } else {
                return $data;
            }
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
