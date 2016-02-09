<?php namespace Modules\Supplier\Services;

class Fuel extends ServiceContract {

	public function emailDataFormat(){
		
		$viewData = [
			'liters' => 'Menge in Liter/Kg',
			'delivery_month' => 'Liefermonat'
		];

		return $viewData;
	}

	/**
	 * Collecting Form Data from contact after the user search
	 * @return [type] [description]
	 */
	function specificValidation()
	{
		return [
			'liters' => 'required|numeric',
			'delivery_month' => 'required',
		];
	}
}