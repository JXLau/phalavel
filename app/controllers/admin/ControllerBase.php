<?php
namespace App\Controllers\Admin;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ControllerBase extends Controller
{
	public function render($view_path, $view)
    {
    	$this->view->setViewsDir(VIEW_PATH . '/admin/');
        $this->view->render($view_path, $view);
    }
}
