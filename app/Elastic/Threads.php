<?php namespace App\Elastic;

class Threads extends ElasticModel
{
	protected static $_index = 'easy_content';
    
    protected static $_type = 'threads';

    public $content;

    public $user_id;
}