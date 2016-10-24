<?php namespace App\Elastic;

class Advice extends Model
{
	protected static $_index = 'advice';
    
    protected static $_type = 'advice';

    public $content;

    public $user_id;
        
    public $priority;

    public $time;

    public $color;

    public $page_id;
}