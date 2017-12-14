<?php

namespace App\Http\Middleware;

use Closure;

class ApiCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $domains = ['http://db.da4.info/', 'http://api.dereban.da', 'http://dereban.da'];
        if (isset($request->server()['HTTP_ORIGIN'])) {
            $origin = $request->server()['HTTP_ORIGIN'];
            if (in_array($origin, $domains)) {
                header('Access-Control-Allow-Origin: '.$origin);
                header('Access-Control-Allow-Headers: Origin, Content-type, Autorization');
            }
        }

        return $next($request);
    }
}