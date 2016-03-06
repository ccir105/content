<?php namespace Modules\Project\Entities;
   
use App\User;
use Illuminate\Database\Eloquent\Model;

use Request;
use Modules\Project\Entities\Page;

class FieldValue extends Model implements BelongsContract{

    protected $fillable = ['field_id','group_id','page_id','order','title','description','slug'];

    protected $table = "field_values";


    public function field(){
        return $this->belongsTo('Modules\Project\Entities\Field','field_id','id');
    }

    public function group(){
        return $this->belongsTo('Modules\Project\Entities\FieldGroup','group_id','id');
    }

    public function getRules( $request ) {

        $rules = [
            'page_id' => 'required_without:group_id|exists:project_pages,id',
            'group_id' => 'required_without:page_id|exists:field_groups,id',
            'field_id' => 'required|exists:fields,id'
        ];

        return $rules;
    }

    public function save( array $options = [] ){

        if( Request::has('page_id') or !is_null( $this->page_id ) ){
            $this->group_id = null;
        }

        else{
            $this->page_id = null;
        }

        if( !isset( $this->id ) ){
            $this->slug = preg_replace('/[\d]+/','',strtolower(str_random(7)));
        }

        return parent::save();
    }

    public static function findBySlug($slug){
        return self::whereSlug($slug)->first();
    }

    public function belongs(User $user )
    {

        $type = ( is_null( $this->group_id ) ) ? 'page' : 'group';

        if( $type == "page" ) {

            $page = Page::find($this->page_id);
        }
        else
        {
            $group = $this->group;
            $page = Page::find($group->page_id);
        }

        if( $page->belongs($user) )
        {
            return true;
        }
    }
}