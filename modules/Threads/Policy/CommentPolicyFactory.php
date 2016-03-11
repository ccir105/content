<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 11:35 PM
 */

namespace Modules\Threads\Policy;

use Illuminate\Http\Request;

class CommentPolicyFactory
{
    public static function make($request, Policy $handler)
    {
        $threadView = new ThreadViewPolicy();

        $threadView->setThread($request->route('thread'));

        $handler->setSuccessor( $threadView );

        return ThreadPolicyFactory::make($request, $handler);
    }
}