<?php 

namespace App\Controllers\Api;

use App\Models\Users;

class GoodController extends ControllerBase
{
	public function sampleAction()
	{
		$data = [
			'code' => '200',
			'msg' => 'ok'
		];
		response($data, 'json');
	}
}