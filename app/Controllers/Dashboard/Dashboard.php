<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Libraries\Template;

class Dashboard extends BaseController
{
	public function index()
	{
		// helper(['url']);
		$data = array(
			'title' => "Dashboard",
			'subtitle' => 'Dashboard Equipment Record'
		);
		return $this->template->render('Dashboard/index', $data);
	}
}
