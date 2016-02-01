<?php namespace Modules\Supplier\Services;

class ServiceFactory{
	function get( $service ){
		$className = "Modules\Supplier\Services\\".$service->class;
		if( class_exists( $className ) ){
			return new $className;
		}
		return false;
	}
}