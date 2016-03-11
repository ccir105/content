<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 6:43 PM
 */

namespace Modules\Threads\Policy;


class ThreadControl extends Policy
{
    protected function process($request)
    {
        $thread = $request->route('thread');

        if( $thread->user_id == $this::$user->id)
        {
            return true;
        }

        if( $this->isManager() )
        {
            return true;
        }

        return false;
    }
}