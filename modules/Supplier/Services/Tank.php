<?php namespace Modules\Supplier\Services;

class Tank extends ServiceContract {
	
	public function emailDataFormat() {
		
		return [
			'storage_capacity' =>'Lagerkapazität in Liter/Kg',
			'stock_liters'=>'Lagerbestand in Liter/Kg',
			'tank'=>'Tank',
			'water_protection'=>'Gewässerschutzzone',
			'last_revision'=>'Letzte Revision',
		];
		
	}

	/**
	 * Collecting Form Data from contact after the user search
	 * @return [type] [description]
	 */
	function specificValidation()
	{
		return [];
	}
}