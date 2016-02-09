<?php namespace Modules\Supplier\Services {

	class Heater extends ServiceContract {

		public function getEmailView(){
			$viewData = [
				'inquiry' => 'Anfrage',
				'room_heat' => 'Raumheizvermögen',
				'heating_kw' => 'Heizleistung kW (optional)'
			];
		}

		/**
		 * Collecting Form Data from contact after the user search
		 * @return [type] [description]
		 */
		function specificValidation()
		{
			return [

			];
		}
	}
}