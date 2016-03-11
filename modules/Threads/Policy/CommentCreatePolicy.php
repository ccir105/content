<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 6:44 PM
 */

namespace Modules\Threads\Policy;


class CommentCreatePolicy extends Policy
{
    protected function process($request)
    {
       return true;
    }
}