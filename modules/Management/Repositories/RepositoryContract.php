<?php namespace Modules\Management\Repositories;

abstract class RepositoryContract{

    protected $instance;

    public function setInstance($instance){
        $this->instance = $instance;
    }

    public function all(){
        return $this->instance->all();
    }

    public function find($id,$relation = null){
        return $this->instance->find($id);
    }
//    public function create($data);
//    public function update($id, $data);
//    public function delete($id);
}