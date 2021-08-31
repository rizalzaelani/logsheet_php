<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;

class IncidentalReport extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Incidental Report',
			'subtitle' => 'List Incidental Report'
		);

		return $this->template->render('IncidentalReport/index', $data);
	}
}
