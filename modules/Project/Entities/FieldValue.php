<?php namespace Modules\Project\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Request;

class FieldValue extends Model {

    protected $fillable = ['field_id','group_id','page_id','order','title','description'];

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

        if( Request::has('page_id') ){
            $this->group_id = null;
        }

        else{
            $this->page_id = null;
        }

        parent::save();
    }
}