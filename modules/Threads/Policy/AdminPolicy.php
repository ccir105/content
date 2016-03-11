<?php namespace Modules\Threads\Policy;

class AdminPolicy extends Policy{

    protected function process( $request )
    {
        if( $this::$user->hasRole('admin') )
        {
            return true;
        }
    }
}