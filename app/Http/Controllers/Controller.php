<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function writeLog($data, $fileName = 'customLog')
    {
        ob_start();
        var_dump($data);
        $result = ob_get_clean();
        \Storage::append($fileName.'.txt', \Carbon\Carbon::now()->format('H:i:s d-m-Y'));
        \Storage::append($fileName.'.txt', $result);
    }

}
