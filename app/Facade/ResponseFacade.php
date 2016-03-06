<?php namespace App\Facade;
use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/2/16
 * Time: 12:53 PM
 */
class ResponseFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'responseHelper';
    }
}