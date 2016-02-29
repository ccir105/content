<?php namespace Modules\Project\Repositories;
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/28/16
 * Time: 1:05 AM
 */
class MainRepository
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

    public function save( $data ){
        $this->model->fill($data);
        $this->model->save();
        return $this->model;
    }

    public function delete(){
        return $this->model->delete();
    }

    public function setInstance($model){
        $this->model = $model;
        return $this;
    }

    public function all(){
        return $this->model->all();
    }

    public function find(){
        return $this->model;
    }

    public function getInstance(){
        return $this->model;
    }
}