<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pivot\UserAdvicePivot;

class Advice extends Model
{
    protected $table = "advices";

    protected $fillable = ['content','priority','time','color','page_id'];

    public function setPageIdAttribute($value)
    {
        $this->attributes['page_id'] = $value ? $value : null;
    }
    
    public function owner()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }

    public static function setUserId($userId)
    {
        $instance = new static();
        $instance->user_id = $userId;
        return $instance;
    }
}
