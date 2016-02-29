<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/28/16
 * Time: 8:19 PM
 */

namespace Modules\Project\Repositories;


class PageRepository extends MainRepository
{
    public function find()
    {
        $groups = $this->model->fieldGroups->load('fields.field');

        $groups->each(function($group){
            $group->is_group = true;
        });

        $fields = $this->model->fields->load('field');

        $fields->each(function($field){
           $field->is_field = true;
        });

        return $fields->merge($groups);
    }
}