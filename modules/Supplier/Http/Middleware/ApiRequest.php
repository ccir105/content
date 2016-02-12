<?php namespace Modules\Supplier\Http\Middleware; 

use Closure;

class ApiRequest {

    const VALIDATION_ERROR = 422;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response =  $next($request);
        
        $statusCode = $response->getStatusCode();

        if( $statusCode == $this::VALIDATION_ERROR ){
            $responseData['message'] = $response->getData();
            $responseData['error'] = 'validation_error';
            $responseData['status'] = false;
            return response()->json($responseData,$statusCode);
        }
                
        return $response;
    }
}
