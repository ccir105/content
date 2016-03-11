<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/6/16
 * Time: 12:32 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    public $table = "resources";

    public function imagable(){
        return $this->morphTo();
    }
}