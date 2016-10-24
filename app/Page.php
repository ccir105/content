<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
   	protected $table = 'user_pages';

   	protected $fillable = ['name','hour_frequency','active','schedule'];
}
