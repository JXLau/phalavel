<?php
namespace App\Controllers;

use App\Foundation\Application;

class HomeController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->motto   = '基于Phalcon优雅的开发框架';
        $this->view->author  = 'Jason Lau';
        $this->view->version = Application::VERSION;
        $this->render('home', 'index');
    }

    public function okAction()
    {
        echo "ok";
    }
}
