<?php

namespace App\Helpers;

class ApiHelper
{
    public static function log($data, $fileName = 'customLog')
    {
        ob_start();
        var_dump($data);
        $result = ob_get_clean();
        \Storage::append($fileName.'.txt', \Carbon\Carbon::now()->format('H:i:s d-m-Y'));
        \Storage::append($fileName.'.txt', $result);
    }
}