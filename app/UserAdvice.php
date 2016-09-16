<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAdvice extends Model
{
    protected $table = "advice_user";

    protected $fillable = ['priority'];
}
