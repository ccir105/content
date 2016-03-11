<?php namespace Modules\Threads\Http\Middleware; 

use Closure;
use Modules\Threads\Policy\ThreadViewPolicy;
use Modules\Threads\Policy\ThreadPolicyFactory as PolicyFactory;
use Modules\Threads\Policy\CommentControl;
use Res;

class CommentDelete {

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

        $commentControlPolicy = new CommentControl();

        $threadPolicy->setSuccessor($commentControlPolicy);

        if( PolicyFactory::make($request,$threadPolicy  ) )
        {
    	    return $next($request);
        }

        return Res::error([],'Unathorized',UNAUTHORIZED);
    }
    
}
