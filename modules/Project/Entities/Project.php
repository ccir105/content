<?php namespace Modules\Project\Entities;
   
use Illuminate\Database\Eloquent\Model;
use App\User;

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

    public function revoke(User $user){
        $query = $this->newQueryWithoutScopes()->from('projects_users');
        return $query->where('project_id',$this->id)->where('user_id',$user->id)->delete();
    }

}