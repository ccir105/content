<?php namespace Modules\Supplier\Scope;
	trait ScopesTrait{
		public function scopeWithIds($query, $idArray){
			return $query->whereIn('id',$idArray);
		}
	}