<?php namespace Modules\Management\Http\Controllers;

use Pingpong\Modules\Routing\Controller;

class ManagementController extends Controller {
	
	public function index()
	{
		return view('management::index');
	}
	
}