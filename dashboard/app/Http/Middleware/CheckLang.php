<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;

class CheckLang
{

    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $lang = $request->header("language") ?? 'ar';
        if(!empty($lang) ) {
            if($lang == 'ar' || $lang == 'en') {
                app()->setLocale($lang);
                config(['state.lang' => $lang]);
                return $next($request);
            }
            return $this->returnError('E100','please choose right language');
        }
    }
}
