<?php

namespace App\Controllers\Reporting;

use App\Controllers\BaseController;

class Equipment extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Equipment',
			'subtitle' => 'List Equipment IPC Logsheet'
		);
		return $this->template->render('Reporting/Equipment/index.php', $data);
	}
}
