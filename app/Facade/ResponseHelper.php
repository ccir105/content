<?php namespace App\Facade;
use Log;
use Illuminate\Http\Request;
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/2/16
 * Time: 12:53 PM
 */
class ResponseHelper
{
    const SUCCESS = 200;
    const FAIL = 400;
    const UNAUTHORIZED = 401;
    const NOT_FOUND = 404;

    private $status;
    
    private $data;
    
    private $message;

    private $statusCode;

    public $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function fail( $data = [], $message = "", $statusCode = self::FAIL)
    {    
        $this->status = 'fail';
        
        $this->data = $data;
        
        $this->message = $message;

        Log::warning($this->message .'=::='. $this->request );
        
        $this->statusCode = $statusCode;
        
        return $this->send();
    }



    public function success( $data = [],$message = "", $statusCode = self::SUCCESS){
        $this->status = 'success';
        $this->data = $data;
        $this->message = $message;
        $this->statusCode = $statusCode;
        return $this->send();
    }

    public function send(){
        return response()->json([ 'status'=> $this->status, 'data' => $this->data, 'message' => $this->message ], $this->statusCode);
    }
}