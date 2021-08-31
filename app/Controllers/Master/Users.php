<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;

class Users extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'List Users | IPC Logsheet',
			'subtitle' => 'List Users'
		);

		return $this->template->render('Users/index', $data);
	}
}
