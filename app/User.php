<?php

namespace App;
use App\Pivot\UserAdvicePivot;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\UserAdvice;
use DB;
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password','mixing','background'
    ];

    public static function getFillables(){
        return [
            'name', 'email'
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

    public $priority = [
        1 => '4',
        2 => '3',
        3 => '2',
        4 => '1'
    ];

    // public function setPasswordAttribute($value){
    //     // $this->attributes['password'] = \Hash::make($value);
    // }

    public static function getResetToken($email)
    {
        return self::from('password_resets')->where('email', $email)->first()->token;
    }

    public function myPages()
    {
        return $this->hasMany('App\Page','user_id','id');
    }
}
