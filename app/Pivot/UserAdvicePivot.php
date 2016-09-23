<?php
namespace App\Pivot;
use Illuminate\Database\Eloquent\Relations\Pivot as PivotTable;

class UserAdvicePivot extends PivotTable {

	const ACTIVE = 1;
	const NOT_ACTIVE = 2;

	const HIGH = 1;
	const MID_HIGH = 2;
	const MID_LOW = 3;
	const LOW = 4;

	protected static $status = [
		self::ACTIVE => 'Active',
		self::NOT_ACTIVE => 'Not Active'
	];

	protected static $priority = [
		self::HIGH => 'Every Day',
		self::MID_HIGH => 'Often',
		self::MID_LOW => 'Sometime',
		self::LOW => 'Rarely'
	];

	protected $appends = ['priority_code'];

	function getStatusAttribute( $value )
	{
		return self::$status[$value]; 
	}

	function getPriorityAttribute( $value )
	{
		return self::$priority[$value];
	}

	function getPriorityCodeAttribute(){
		return array_search($this->priority, self::$priority);
	}
}