<?php

namespace App\Exceptions;

use Exception;

class ApiValidationException extends Exception
{
    protected $validate = '';

    protected $code = 500;

    public function __construct()
    {
        
    }

    public function render()
    {
        return response()->json(['validate'=>$this->validate], $this->code);
    }

    public function withValidator($validator)
    {
        $this->validate = $validator->getMessageBag()->toArray();
        return $this;
    }

    public function withCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
    



