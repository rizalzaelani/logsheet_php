<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;

class Transaction extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Transaction',
			'subtitle' => 'Transaction'
		);

		return $this->template->render('Transaction/index', $data);
	}
}
