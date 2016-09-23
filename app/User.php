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

    public $priority = [
        1 => '4',
        2 => '3',
        3 => '2',
        4 => '1'
    ];

    // public function setPasswordAttribute($value){
    //     $this->attributes['password'] = \Hash::make($value);
    // }

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

    public static function getResetToken($email)
    {
        return self::from('password_resets')->where('email', $email)->first()->token;
    }

    public function myAdviceByPrority()
    {    
        $returnData = [];
        
        $query = null;

        foreach ($this->priority as $pId => $limit) {
            
            $oneQuery =UserAdvice::where([
                ['user_id',$this->id],
                ['priority',$pId]
            ])->orderBy( 'created_at','desc' )->take($limit);

            $query = is_null($query) ? $oneQuery : $query->union($oneQuery);
        }

        $advices = $query->get();

        $adviceRaw = [];

        foreach($advices as $advice){
            $adviceRaw[ $advice->advice_id ] = $advice;
        }

        $adviceIds = array_keys($adviceRaw);

        $adviceModels = Advice::whereIn('id', $adviceIds)->get();

        foreach($adviceModels as $model)
        {
            $pivot = $adviceRaw[$model->id];
            $adviceRaw[ $model->id ] = array_merge($model->toArray(), ['pivot' => $pivot]);
        }


        foreach ($adviceRaw as $key => $value) 
        {
            $returnData[ $value['pivot']['priority'] ][] = ['text' => $value['text'], 'id' => $value['id']];
        }

        return $returnData;
    }
}
