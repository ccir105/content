<?php namespace Modules\Threads\Http\Middleware; 

use Closure;
use Modules\Threads\Policy\ThreadViewPolicy;
use Modules\Threads\Policy\ThreadPolicyFactory as PolicyFactory;
use Res;
class ThreadView {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $thread = $request->route('thread');

        $threadPolicy = new ThreadViewPolicy();

        $threadPolicy->setThread($thread);

        if(PolicyFactory::make($request,$threadPolicy  ) )
        {
    	    return $next($request);
        }
        return Res::error([],'Unathorized',UNAUTHORIZED);
    }
    
}
