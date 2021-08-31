<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;

class Transaction extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Transaction',
			'subtitle' => 'Checklist Equipment Record IPC Logsheet'
		);

		return $this->template->render('Transaction/index', $data);
	}
}
