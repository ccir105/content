<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/7/16
 * Time: 11:48 PM
 */

namespace Modules\Threads\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use App\Comment;
use Modules\Threads\Entities\Thread;
use Pingpong\Modules\Routing\Controller;
use Res;
use Modules\Threads\Repositories\CommentRepo;
use Modules\Threads\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    protected $comment;
    protected $repo;

    public function __construct(Comment $comment,Guard $guard)
    {
        $this->comment = $comment;
        $this->repo = new CommentRepo();
        $this->repo->setUser($guard->user());
    }

    public function create(CommentRequest $request, Project $project, Thread $thread)
    {
        return Res::success($this->repo->save($request->all() , $thread));
    }

    public function edit(CommentRequest $request, Project $project, Thread $thread, Comment $comment)
    {
        return Res::success($this->repo->edit($request->all(),$comment,$thread));
    }

    public function delete(Request $request, Project $project, Thread $thread, Comment $comment)
    {
        return Res::success($this->repo->delete($comment));
    }
}