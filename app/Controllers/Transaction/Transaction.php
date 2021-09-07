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

	public function detail()
	{
		$data = array(
			'title' => 'Detail Transaction',
			'subtitle' => 'Detail Transaction'
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
			[
				"title"	=> "Detail",
				"link"	=> ""
			],
		];
		return $this->template->render('Transaction/detail', $data);
	}
}
