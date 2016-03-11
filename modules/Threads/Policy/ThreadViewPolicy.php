<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 5:23 PM
 */

namespace Modules\Threads\Policy;


use Modules\Threads\Entities\Thread;

class ThreadViewPolicy extends Policy
{
    protected function process($request)
    {
        $thread = ($this->thread) ?: $request->route('thread');

        if (!$this->isManager() )
        {
            if ( $this::$user->isMyThread( $thread ) )
            {
                return true;
            }

            if ( $this->thread->isAssigned( $this::$user ) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        return true;
    }
}