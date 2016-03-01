<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/29/16
 * Time: 11:42 AM
 */

namespace Modules\Project\Http\Controllers;
use App\Http\Requests\Request;
use Modules\Project\Entities\Page;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\ProjectRequest;
use Pingpong\Modules\Routing\Controller;
use App\Traits\CrudTrait;

class PageController extends Controller
{
    use CrudTrait;

    public function __construct(Page $page)
    {
        $this->setInstance($page);
    }

    public function update( ProjectRequest $request, $model ){
        $this->setInstance( $model );
        return $this->save( $request->all() );
    }

    public function remove( $model ){
        return ['status' => $this->setInstance( $model )->delete() ];
    }

    public function create(ProjectRequest $request){
        return $this->save($request->all());
    }

    public function find( $model )
    {
        $groups = $model->fieldGroups->load('fields.field');

        $groups->each(function($group){
            $group->is_group = true;
        });

        $fields = $model->fields->load('field');

        $fields->each(function($field){
            $field->is_field = true;
        });



        return $groups->merge($fields);
    }
}
