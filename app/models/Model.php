<?php
namespace App\Models;

use Phalcon\Mvc\Model as PhalconModel;

abstract class Model extends PhalconModel
{
	public function setConnectionService($db_name)
    {
    	$db_slave = $db_name . '_slave';
    	if (app()->has($db_slave)) {
    		$this->setReadConnectionService($db_slave);
            $this->setWriteConnectionService($db_name);
            return;
    	}
    	return parent::setConnectionService($db_name);
    }
}
