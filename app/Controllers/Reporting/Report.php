<?php

namespace App\Controllers\Reporting;

use App\Controllers\BaseController;

class Report extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Report',
			'subtitle' => 'Export Data Application IPC Logsheet'
		);

		return $this->template->render('Reporting/Report/index', $data);
	}
}
