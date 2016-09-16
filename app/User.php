<?php

namespace App;
use App\Pivot\UserAdvicePivot;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password','mixing','background'
    ];

    public static function getFillables(){
        return [
            'name', 'email', 'password','mixing','background'
        ];
    }
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value){
        $this->attributes['password'] = \Hash::make($value);
    }

    public function myAdvice($type = null){
        $query = $this
            ->belongsToMany('App\Advice','advice_user','user_id','advice_id')
            ->withPivot('status','priority');
        return is_null($type) ? $query : $query->where('advices.type', $type); 
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        return new UserAdvicePivot($parent, $attributes, $table, $exists);
    }
}
