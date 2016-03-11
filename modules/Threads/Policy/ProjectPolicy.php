<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 5:00 PM
 */

namespace Modules\Threads\Policy;


class ProjectPolicy extends Policy
{

    protected function process($request)
    {
        $project = ($this::$project) ? $this::$project : $request->route('project');

        if( $project->belongs($this::$user) )
        {
            if( !is_null( $this->successor ) )
            {
                return $this->successor->process($request);
            }

            return true;
        }

        return false;
    }
}