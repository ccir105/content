<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/1/16
 * Time: 11:09 PM
 */

namespace Modules\Project\Http\Controllers;
use Illuminate\Contracts\Auth\Guard;
use Modules\Project\Entities\FieldValue;
use Modules\Project\Entities\Page;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use Res;
class ClientController extends Controller
{
    protected $user;

    public function __construct(Guard $guard)
    {
        $this->user = $guard->user();
    }

    public function myProject()
    {
        return $this->user->projects->load('assignedUsers');
    }

    public function saveForm(Request $request,Page $page)
    {
        if($page->belongs($this->user) )
        {
            $slugs = $page->getAllEntitiesSlug();

            foreach($slugs as $slug)
            {
               if( $request->has( $slug ) )
               {
                    $fieldValue = FieldValue::findBySlug($slug);

                    $fieldValue->value = $request->get($slug);

                    $fieldValue->save();
               }
            }

           return Res::success([],'Page data are saved successfully');
        }

        return Res::fail([],'Unathorized', UNAUTHORIZED);
    }
}