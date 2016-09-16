<?php

namespace App\Http\Middleware;
use JWTAuth;
use Closure;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Res;

class ApiRequest extends BaseMiddleware
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

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 403);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', 403, [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', 403, [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 403);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        \Auth::loginUsingId( $user->id );

        return $next($request);
    }

    public function respond($data, $message, $status, $payload = array()){
        return Res::fail($payload, $message, $status);
    }
}
