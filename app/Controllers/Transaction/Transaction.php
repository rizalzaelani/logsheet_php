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


		$data["breadcrumbs"] = [ "home", "transaction"];
		return $this->template->render('Transaction/index', $data);
	}
}
