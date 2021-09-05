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

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Transaction",
				"link"	=> "Transaction"
			],
		];
		return $this->template->render('Transaction/index', $data);
	}
}
