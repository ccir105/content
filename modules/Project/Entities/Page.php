<?php namespace Modules\Project\Entities;
   
use App\User;
use Illuminate\Database\Eloquent\Model;

class Page extends Model implements BelongsContract{

    protected $fillable = ['project_id','title','order','description'];

    protected $table = "project_pages";

    public function project(){
        return $this->belongsTo('Modules\Project\Entities\Project','project_id','id');
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

    public function getEntities(){

        $groups = $this->fieldGroups->load('fields.field');

        $groups->each(function($group){
            $group->is_group = true;
        });

        $fields = $this->fields->load('field');

        $fields->each(function($field){
            $field->is_field = true;
        });

        return $groups->merge($fields);
    }

    public function getAllEntitiesSlug(){

        $slugs = [];

        $this->getEntities()->each(function($entity)use (&$slugs){
            if( isset( $entity->is_group ) ){
                $slugs = array_merge($entity->fields->lists('slug')->toArray(),$slugs);
            }
            else
            {
                $slugs[] = $entity->slug;
            }
        });

        return $slugs;
    }

    public function belongs(User $user){
        return $this->project->belongs($user);
    }
}