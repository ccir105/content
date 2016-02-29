<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/28/16
 * Time: 8:19 PM
 */

namespace Modules\Project\Repositories;


class FieldGroupRepository extends MainRepository
{
    public function find()
    {
        return $this->model->fields->load('field');
    }
}