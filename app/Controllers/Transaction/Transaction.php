<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Models\ScheduleTrxModel;
use App\Models\TransactionModel;

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
		$scheduleTrxId = $this->request->getVar("scheduleTrxId") ?? "";

		if($scheduleTrxId == ""){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();

		$getSchedule = $scheduleTrxModel->getById($scheduleTrxId);
		if(empty($getSchedule)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data["scheduleTrxData"] = $getSchedule;
		$data["trxData"] = $trxModel->getAll(["scheduleTrxId" => $scheduleTrxId]);

		$data['title'] = 'Detail Transaction';
		$data['subtitle'] = 'Detail Transaction';
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
		// print("<pre />");
		// print_r($data);
	}

	public function datatable()
	{
		$table = 'vw_scheduleTrx';
		$column_order = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'schType', 'scheduleTrxId');
		$column_search = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'schType', 'scheduleTrxId');
		$order = array('createdAt' => 'asc');
		$request = \Config\Services::request();
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
		$where = [
			'scannedAt IS NOT NULL' => null
		];
		$list = $DTModel->datatable($where);
		$output = array(
			"draw" => $request->getPost('draw'),
			"recordsTotal" => $DTModel->count_all($where),
			"recordsFiltered" => $DTModel->count_filtered($where),
			"data" => $list,
			'status' => 200,
			'message' => 'success'
		);
		echo json_encode($output);
	}
}
