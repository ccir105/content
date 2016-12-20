<?php namespace App\Elastic;

class Project extends ElasticModel
{
	protected static $_index = 'easy_content';
    
    protected static $_type = 'project';

    public $content;

    public $user_id;
}