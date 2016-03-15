<?php namespace Modules\Project\Entities;
   
use Illuminate\Database\Eloquent\Model;
use App\User;

class Project extends Model {

    protected $fillable = ['title','description','status'];

    protected $table = "projects";

    public function pages(){
        return $this->hasMany('Modules\Project\Entities\Page','project_id','id');
    }

    public function assignedUsers(){
        return $this->belongsToMany('App\User','projects_users','project_id','user_id');
    }

    public function assign(User $user){

        $query = $this->newQueryWithoutScopes();

        $isAssigned = $query->from('projects_users')->where('user_id',$user->id)->where('project_id',$this->id)->first();

        if( is_null($isAssigned) ){
            $query->from('projects_users')->insert([
                'project_id' => $this->id,
                'user_id' => $user->id
            ]);
        }
        return true;
    }

    public function users(){
        return $this->belongsToMany('App\User','projects_users','project_id');
    }

    public function belongs(User $user)
    {
       return in_array($user->id,$this->users->lists('id')->toArray());
    }

    public function revoke(User $user){
        $query = $this->newQueryWithoutScopes()->from('projects_users');
        return $query->where('project_id',$this->id)->where('user_id',$user->id)->delete();
    }

    public function duplicate()
    {
        $duplicate = new Project();

        $duplicate->fill($this->toArray());

        $duplicate->save();

        $pages = $this->pages;

        if( !$pages->isEmpty() )
        {
            $this->pages->each(function($page) use( $duplicate )
            {
                $newPage = new Page();

                $newPage->fill($page->toArray());

                $newPage->project_id = $duplicate->id;

                $newPage->save();

                $entities = $page->getEntities();

                if( !$entities->isEmpty() )
                {
                    $entities->each(function($entity) use( $newPage )
                    {
                       if( isset( $entity->is_group ) )
                       {
                            $fieldGroup = new FieldGroup();
                            $fieldGroup->fill($entity->getAttributes());
                            $fieldGroup->page_id = $newPage->id;
                            $fieldGroup->save();

                            if( !$entity->fields->isEmpty() )
                            {
                                $entity->fields->each(function($field) use($newPage,$fieldGroup)
                                {
                                    $this->saveFieldValue($field, null, $fieldGroup->id);
                                });
                            }
                       }
                       else
                       {
                            $this->saveFieldValue($entity, $newPage->id);
                       }

                    });
                }

            });
        }

        return $duplicate;
    }

    public function saveFieldValue($field , $pageId = null, $groupId = null)
    {
        $newField = new FieldValue();
        $newField->fill($field->toArray());
        $newField->value = "";
        $newField->page_id = $pageId;
        $newField->group_id = $groupId;
        $newField->save();
    }

    public function threads(){
        return $this->hasMany('Modules\Threads\Entities\Thread','project_id','id');
    }
}