<?php

namespace App\Exceptions;

use Exception;

class ApiCustomException extends Exception
{
    protected $code = 400;

    protected $message = 'Somerthing whent wrong';

    public function __construct()
    {

    }

    public function render()
    {
        return response()->error($this->message, $this->code);
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




