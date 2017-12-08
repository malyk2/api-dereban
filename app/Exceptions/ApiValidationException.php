<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;

class ApiValidationException extends Exception
{
    protected $validate = '';

    protected $code = 422;

    protected $message = 'Validate error';

    public function __construct()
    {
        
    }

    public function render()
    {
        $response = [
            'message' => $this->message,
            'validate' => $this->validate
        ];
        return response()->json($response, $this->code);
    }

    public function withValidator(Validator $validator)
    {
        $this->validate = $validator->getMessageBag()->toArray();
        return $this;
    }

    public function withCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function withMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
    



