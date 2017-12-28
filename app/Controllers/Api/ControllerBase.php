<?php
namespace App\Controllers\Api;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	public function initialize()
	{
		define("DONT_PROFILE", 1);
		$this->view->disable();
	}
}
