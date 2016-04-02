<?php namespace Modules\Project\Entities;
   
use App\User;
use Illuminate\Database\Eloquent\Model;

class FieldGroup extends Model implements BelongsContract{



    protected $fillable = ['page_id','title','description','order'];

    protected $table = "field_groups";

    public function fields(){
        return $this->hasMany('Modules\Project\Entities\FieldValue','group_id','id');
    }

    public function getRules($request){
        return [

        ];
    }

    public function belongs(User $user){
        $page = Page::find( $this->page_id );
        if($page->belongs( $user ) ){
            return true;
        }
    }
}