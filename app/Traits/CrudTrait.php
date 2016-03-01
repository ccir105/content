<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/29/16
 * Time: 1:45 PM
 */

namespace App\Traits;


trait CrudTrait
{
    protected $model;

    public function save( $data ){
        $this->model->fill($data);
        $this->model->save();
        return $this->model;
    }

    public function delete($model) {
        $model->delete();
        return ['status' => 1 ];
    }

    public function setInstance( $model ){
        $this->model = $model;
        return $this;
    }

    public function all(){
        return $this->model->all();
    }

    public function find($model){
        return $model;
    }

    public function getInstance(){
        return $this->model;
    }
}