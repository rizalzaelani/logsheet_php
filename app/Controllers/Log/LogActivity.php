<?php

namespace App\Controllers\Log;

use App\Controllers\BaseController;

class LogActivity extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Log Activity',
			'subtitle' => 'Log Activity Users'
		);

		return $this->template->render('LogActivity/index', $data);
	}
}
