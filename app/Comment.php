<?php namespace App;
   
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    protected $fillable = ['text'];

    public function commentable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}