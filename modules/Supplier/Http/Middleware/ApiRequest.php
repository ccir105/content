<?php namespace Modules\Supplier\Http\Middleware; 

use Closure;

class ApiRequest {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	return $next($request);
    }
}
