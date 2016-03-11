<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exception\HttpResponseException;
use Res;
use App\Facade;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render( $request, Exception $e )
    {
        if( $e instanceof ModelNotFoundException )
        {
            return Res::fail([],'The Entity is not found',NOT_FOUND);
        }


        if( $e instanceof HttpResponseException )
        {
            if(method_exists($e, 'getStatusCode') and $e->getStatusCode() == ACCESS_FORBIDDEN)
            {
                return Res::fail([],'Access Forbidden',ACCESS_FORBIDDEN);
            }

            if(method_exists($e,'getResponse'))
            {
                $response = $e->getResponse();

                $response->getStatusCode();

                if($response->getStatusCode() == UNPROCESSED_ENTITY)
                {
                   return Res::fail($response->getData(),'Validation Error',UNPROCESSED_ENTITY);
                }
            }
        }


        if( (method_exists($e,'getStatusCode') and $e->getStatusCode() == ACCESS_FORBIDDEN) || $e instanceof HttpResponseException){
            return Res::fail([],'Access Forbidden',ACCESS_FORBIDDEN);
        }

        if($e instanceof HttpException){
            return Res::fail([],'Not Found',NOT_FOUND);
        }

        return parent::render( $request,  $e);
    }
}
