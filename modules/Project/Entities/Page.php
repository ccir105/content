<?php namespace Modules\Project\Entities;
   
use Illuminate\Database\Eloquent\Model;

class Page extends Model {

    protected $fillable = ['project_id','title','order','description'];

    protected $table = "project_pages";

    public function project(){
        return $this->belongsTo('Modules\Management\Entities\Project','project_id','id');
    }

    public function fieldGroups(){
        return $this->hasMany('Modules\Project\Entities\FieldGroup','page_id','id');
    }

    public function fields(){
        return $this->hasMany('Modules\Project\Entities\FieldValue','page_id','id');
    }

    public function getRules($request){
        return [
            'project_id' => 'exists:projects,id'
        ];
    }
}