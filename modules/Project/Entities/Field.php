<?php namespace Modules\Project\Entities;
   
use Illuminate\Database\Eloquent\Model;
use App\User;

class Field extends Model implements BelongsContract{

    protected $fillable = [];

    protected $table = "fields";

    public function belongs(User $user)
    {
        // TODO: Implement belongs() method.
    }

}