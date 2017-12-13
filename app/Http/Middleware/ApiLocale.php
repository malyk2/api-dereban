<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class ApiLocale
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
        if (auth()->check()) {
            App::setLocale(auth()->user()->lang);
        } else {
            $this->setLocale($request);
        }
        return $next($request);
    }

    private function setLocale($request)
    {
        $locale = $request->input('lang');
        if ( ! empty($locale))
        {
            if (array_key_exists($locale, config('app.locales'))) {
                App::setLocale($locale);
            } else {
                App::setLocale(config('app.fallback_locale'));
                $request->addError('Lang not found', 'warning');
            }
        }
    }

}
