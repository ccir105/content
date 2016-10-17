<?php namespace App\Elastic;

use Isswp101\Persimmon\QueryBuilder\QueryBuilder as BaseQueryBuilder;

class QueryBuilder extends BaseQueryBuilder{
	
	protected $model;

	public function __constuct($model)
	{
		$this->model = $model;
		parent::__constuct();
	}	

	public function get()
	{
		return $this->model->search($this);
	}
}