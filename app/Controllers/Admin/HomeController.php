<?php
namespace App\Controllers\Admin;

class HomeController extends ControllerBase
{
	public function indexAction()
	{
		$this->render('admin/home', 'index');
	}
}