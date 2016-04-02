<?php

namespace App\Http\Middleware;

use Closure;

class BelongsToProject
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
        if($project= $request->route('project'))
        {
            $user = \Auth::user();

            if( $user->hasRole('admin') )
            {
                return $next($request);
            }

            if($project->belongs($user) )
            {
                if(isset($project->users))
                {
                    unset($project->users);
                }

                return $next($request);
            }

            \App::abort(403);
        }

        return $next($request);
    }
}
