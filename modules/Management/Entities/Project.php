<?php namespace Modules\Management\Entities;
   
use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    protected $fillable = ['title','description','status'];

    protected $table = "projects";

    public function pages(){
        return $this->hasMany('Modules\Project\Entities\Page','project_id','id');
    }

    public function save(array $options = []){
        $this->user_id = \Auth::user()->id;
        return parent::save();
    }

}