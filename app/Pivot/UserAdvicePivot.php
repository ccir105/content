<?php
namespace App\Pivot;
use Illuminate\Database\Eloquent\Relations\Pivot as PivotTable;

class UserAdvicePivot extends PivotTable {

	const ACTIVE = 1;
	const NOT_ACTIVE = 2;

	const EVERY_DAY = 1;
	const OFTEN = 2;
	const SOMETIME = 3;
	const RARELY = 4;

	protected static $status = [
		self::ACTIVE => 'Active',
		self::NOT_ACTIVE => 'Not Active'
	];

	protected static $priority = [
		self::EVERY_DAY => 'Every Day',
		self::OFTEN => 'Often',
		self::SOMETIME => 'Sometime',
		self::RARELY => 'Rarely'
	];

	function getStatusAttribute( $value )
	{
		return self::$status[$value]; 
	}

	function getPriorityAttribute( $value )
	{
		return self::$priority[$value];
	}
}