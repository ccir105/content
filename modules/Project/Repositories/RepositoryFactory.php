<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/28/16
 * Time: 4:06 PM
 */

namespace Modules\Project\Repositories;


use Illuminate\Support\Facades\Request;

class RepositoryFactory
{
    public static $repo = null;

    public static $models = [

        'project' => [
            'model' => 'Modules\Project\Entities\Project',
            'repository' => 'Modules\Project\Repositories\ProjectRepository'
        ],

        'page' => [
            'model' => 'Modules\Project\Entities\Page',
            'repository' => 'Modules\Project\Repositories\PageRepository'
        ],

        'field' => [
            'model' => 'Modules\Project\Entities\FieldValue',
            'repository' => 'Modules\Project\Repositories\FieldRepository'
        ],

        'field-group' => [
            'model' => 'Modules\Project\Entities\FieldGroup',
            'repository' => 'Modules\Project\Repositories\FieldGroupRepository'
        ],
    ];


    public static function make()
    {
        if( !is_null( self::$repo ) ) return self::$repo;

        $class = Request::segment(2);

        if(in_array($class, array_keys(self::$models )))
        {
            $class = self::$models[ $class ];

            $repo = "Modules\Project\Repositories\MainRepository";

            if(is_array( $class ) )
            {
                $repo = isset( $class['repository'] ) ? $class['repository'] : $repo;

                $class = $class['model'];
            }

            $model = new $class;

            return $model;
        }
    }
}