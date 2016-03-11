<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 10:16 PM
 */

namespace Modules\Threads\Policy;

use Auth;

class ThreadPolicyFactory
{
    protected static $lastPolicy;

    public static function make( $request ,$handler = null)
    {
        AdminPolicy::$project = $request->route('project');

        AdminPolicy::$user = Auth::user();

        $adminPolicy = new AdminPolicy();

        $projectPolicy = new ProjectPolicy();

        $adminPolicy->setSuccessor( $projectPolicy );

        if( !is_null( $handler ) )
        {
            $projectPolicy->setSuccessor( $handler );
            self::$lastPolicy = $projectPolicy;
        }


        return $adminPolicy->handle($request);
    }

    public static function getLast()
    {
        return self::$lastPolicy;
    }
}