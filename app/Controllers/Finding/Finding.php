<?php

namespace App\Controllers\Finding;

use App\Controllers\BaseController;

class Finding extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Finding',
			'subtitle' => 'All Finding IPC Logsheet'
		);

		return $this->template->render('Finding/index', $data);
	}
}
