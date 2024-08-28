<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;


class CheckPassword
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

        //return request()->header();
        //return getallheaders();


        if(getallheaders() != null && ! empty(getallheaders())) {

    
            return $next($request);

        } 




    }


}
