<?php
namespace App\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function render($view_path, $view)
    {
        $this->view->render($view_path, $view);
    }
}
