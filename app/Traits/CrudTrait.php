<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/29/16
 * Time: 1:45 PM
 */

namespace App\Traits;
use Res;

trait CrudTrait
{
    protected $model;

    public function save( $data ){

        $this->model->fill($data);

        if( $this->model->save() ){
            return $this->model;
        }

        return Res::fail([],'The operation is invalid',FAIL);
    }

    public function delete($model) {
        $model->delete();
        return Res::success([],'Successfully Deleted');
    }

    public function setInstance( $model ){
        $this->model = $model;
        return $this;
    }

    public function all(){
        return Res::success($this->model->all());
    }

    public function find($model){
        return Res::success($model);
    }

    public function getInstance(){
        return $this->model;
    }

    public function belongs($model, $user){
        if( !$user->hasRole('admin') ){
            if(!$model->belongs($user) ){
                \App::abort(403);
            }

            if(isset($model->users)){
                unset($model->users);
            }
        }

        return true;
    }
}