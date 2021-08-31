<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ApiModel;

class Api extends BaseController
{
	public function index()
	{
		$model = new ApiModel();
		$data = [
			'data' => $model->findAll()
		];

		// $json = json_encode($data);
		return json_encode($data);
	}
}
