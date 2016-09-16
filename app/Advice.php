<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pivot\UserAdvicePivot;
class Advice extends Model
{
    protected $table = "advices";

    protected $fillable = ['text'];
    
    public function owner()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }

    public function isGlobal()
    {
    	return $this->type == '1';
    }

    public function scopeGlobals($query)
    {
    	return $query->where('type','1');
    }
    
    public function users()
    {
    	return $this->belongsToMany('App\User','advice_user','advice_id','user_id');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        return new UserAdvicePivot($parent, $attributes, $table, $exists);
    }
}
