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

			$basicForm = [
				'company_name' => 'company',
				'name' => 'person',
				'email' => 'envelop',
				'phone' => 'phone',
			];

			$request->merge([
				'name' => $request->get('first_name') . ' ' . $request->get('last_name')
			]);

			$otherInfo = [
				'street' => 'Strasse',
				'postal_code' => 'Postleitzahl',
				'city' => 'Ort',
				'country' => 'Land'
			];

			$basicForm = $this->makeViewData($request, $basicForm);
			
			$otherInfo = $this->makeViewData($request, $otherInfo);

			$adminEmail = env('ADMIN_EMAIL','sisnet2010@gmail.com');

			$viewData = [ 'basic_form' => $basicForm, 'other_info' => $otherInfo ];

			$viewData['description'] = $request->has('description') ? $request->get('description') : false;

			return $this->sendTemplateEmail([
				'template' => 'email.new-supplier',
				'view_data' => $viewData,
				'to_email' => $adminEmail,
				'subject' => 'Lieferanten Kontaktanfrage'
			]);
		}

		public function makeViewData($request, $userData){
			
			$viewData = [];

			foreach ($userData as $key => $name) {
				if( $request->has( $key ) && trim( $request->get( $key ) ) != "" ){
					$viewData[$name] = $request->get( $key );
				}
			}
			return $viewData;
		}

		public function sendContactEmail($request){

			$userData = [
				'name' => 'person',
				'email' => 'envelop',
				'phone' => 'phone',
			];

			$viewData = $this->makeViewData($request, $userData);

			$adminEmail = env('ADMIN_EMAIL','sisnet2010@gmail.com');

			return $this->sendTemplateEmail([
				'template' => 'email.contact',
				'view_data' => ['contact_data' => $viewData,'desc' => $request->get('description')],
				'to_email' => $adminEmail,
				'subject' => 'Kontaktanfrage'
			]);
		}
	}
