<?php namespace Modules\Supplier\Services;

class ServiceFactory {
	static public $service;

	static function make( $service ){
		$className = "Modules\Supplier\Services\\".ucfirst($service->class);
		if( class_exists( $className ) ){
			self::$service = new $className($service);
			return self::$service;
		}
		return false;
	}

	static function getService(){
		return self::$service;
	}
}