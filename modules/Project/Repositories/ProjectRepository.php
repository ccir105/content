<?php namespace Modules\Project\Repositories;
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/28/16
 * Time: 1:05 AM
 */
class ProjectRepository extends MainRepository
{

    /*
     * project
     * page
     * project
     * field
     * field-group
     *
     * */

    // so now repitetive finding

    protected $model;

    //based on the user roles the projects will be loaded
    public function find(){
        return $this->model->load('pages');
    }
}