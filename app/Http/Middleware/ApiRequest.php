<?php

namespace App\Http\Middleware;
use JWTAuth;
use Closure;

class ApiRequest
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
        if( $user = JWTAuth::toUser() )
        {
            \Auth::loginUsingId( $user->id );
        }

        return $next( $request );
    }
}
