<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/6/16
 * Time: 3:02 PM
 */

namespace Modules\Threads\Repositories;

use App\Comment;
use App\User;

class CommentRepo
{
    protected $comment;

    protected $user;

    public function __construct()
    {
        $this->comment = new Comment();
    }

    public function save( $data, $thread )
    {
        $this->comment->fill($data);

        $this->comment->user_id = $this->user->id;

        $thread->comments()->save( $this->comment );

        $comment = $this->comment;

        $this->comment = new Comment();

        return $comment;
    }

    public function setUser(User $user = null)
    {
        $this->user = (is_null($user)) ? \Auth::user() : $user;
        return $this;
    }

    public function edit( $data, $comment, $thread)
    {
        $comment->fill( $data );
        $thread->comments()->save( $comment );
        return $comment;
    }

    public function find( $comment = null )
    {

        if( is_integer( $comment ) ) {
            $thread = $this->comment->find($comment);
        }

        return is_null( $comment ) ? false : $comment;
    }

    public function delete( $comment )
    {
        return $comment->delete();
    }
}