<?php 

namespace App\Controllers\Api;

use App\Models\Users;

class GoodController extends ControllerBase
{
	public function sampleAction()
	{
		$data = Users::findFirst(1);

		$data = [
			'code' => '200',
			'msg' => 'ok',
			'data' => $data
		];
		response($data, 'json');
	}
}