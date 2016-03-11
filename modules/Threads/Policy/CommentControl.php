<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 6:44 PM
 */

namespace Modules\Threads\Policy;


class CommentControl extends Policy
{
    protected function process( $request)
    {
        $response = $this->successor->handle( $request );

        if( $response )
        {
            $comment = $request->route('comment');

            if( $comment->user_id == $this::$user->id )
            {
                return true;
            }

            if( $this->isManager() )
            {
                if( $comment->user->hasRole('client') )
                {
                    return false;
                }

                return true;
            }
        }

        return false;
    }
}