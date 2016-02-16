<?php namespace Modules\Supplier\Scope;
use Mail;
	trait EmailTrait{
		public function sendTemplateEmail( $options ){

			$fromEmail = getenv('FROMEMAIL');

	        $fromName = getenv('FROMNAME');  

	        $toEmail = $options['to_email'];

	        $toName = isset($options['to_name']) ? $options['to_name'] : "";

	        $subject = isset($options['subject']) ? $options['subject'] : "Contact";

			return Mail::send( $options['template']  , $options['view_data'] , function( $m ) use( $toEmail, $fromEmail, $fromName, $toName,$subject ){
				$m->from( $fromEmail, $fromName );
				$m->to( $toEmail , $toName )->subject($subject);
			});
		}

		public function sendNewSupplierContactEmail($request){

			$userData = [
				'company_name' => 'Firma',
				'first_name' => 'Vorname',
				'last_name' => 'Nachname',
				'email' => 'E-Mail Adresse',
				'phone' => 'Telefon',
				'street' => 'Strasse',
				'postal_code' => 'Postleitzahl',
				'city' => 'Ort',
				'country' => 'Land',
				'description' => 'Nachricht',
			];

			$viewData = $this->makeViewData($request, $userData);

			$adminEmail = env('ADMIN_EMAIL','sisnet2010@gmail.com');

			return $this->sendTemplateEmail([
				'template' => 'email.new-supplier',
				'view_data' => ['supp_data' => $viewData],
				'to_email' => $adminEmail,
				'subject' => 'Lieferanten Kontaktanfrage'
			]);
		}

		public function makeViewData($request, $userData){
			
			$viewData = [];

			foreach ($userData as $key => $name) {
				if( $request->has($key) ){
					$viewData[$name] = $request->get($key);
				}
			}
			return $viewData;
		}

		public function sendContactEmail($request){

			$userData = [
				'name' => 'Name',
				'email' => 'Email',
				'phone' => 'Telefon',
				'description' => 'Nachricht',
			];

			$viewData = $this->makeViewData($request, $userData);

			$adminEmail = env('ADMIN_EMAIL','sisnet2010@gmail.com');

			return $this->sendTemplateEmail([
				'template' => 'email.contact',
				'view_data' => ['contact_data' => $viewData],
				'to_email' => $adminEmail,
				'subject' => 'Kontaktanfrage'
			]);
		}
	}
