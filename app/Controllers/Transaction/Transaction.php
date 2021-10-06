<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Models\AttachmentTrxModel;
use App\Models\ScheduleTrxModel;
use App\Models\TransactionModel;
use DateTime;

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
		$attachmentTrxModel = new AttachmentTrxModel();

		$getSchedule = $scheduleTrxModel->getById($scheduleTrxId);
		if(empty($getSchedule)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data["scheduleTrxData"] = $getSchedule;
		$data["trxData"] = $trxModel->getAll(["scheduleTrxId" => $scheduleTrxId]);
		$data["attachmentTrxData"] = $attachmentTrxModel->getWhere(["scheduleTrxId" => $scheduleTrxId])->getResultArray();

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
	}

	public function datatable()
	{
		$table = 'vw_scheduleTrx';
		$column_order = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'approvedAt', 'scheduleTrxId');
		$column_search = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'approvedAt', 'scheduleTrxId');
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

	public function approveTrx(){
        $isValid = $this->validate([
            'scheduleTrxId' => 'required',
            'approvedNotes' => 'required',
        ]);

		$dateNow = new DateTime();

        if (!$isValid) {
            return $this->response->setJson([
                'status' => 400,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }
		
		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();

		$scheduleTrxId = $this->request->getVar("scheduleTrxId");
		$approvedNotes = $this->request->getVar("approvedNotes");
		$condition = "Normal";
		
		$getNormalAbnormal = $scheduleTrxModel->checkNormalAbnormal($scheduleTrxId);
		if(!empty($getNormalAbnormal)){
			$trxData = $trxModel->getAll(["scheduleTrxId" => $scheduleTrxId]);
			$updateTrx = [];
			foreach($trxData as $row){
				array_filter($getNormalAbnormal, function($val) use ($row){
					if($val["abnormal"]	== 1 && $val["trxId"] == $row["trxId"]){
						array_push($updateTrx, array(
							"trxId" => $val["trxId"],
							"condition" => "Finding" 
						));
						return true;
					}
				});
			}
		}

		$cekSch = $scheduleTrxModel->getById($scheduleTrxId);
		if(empty($cekSch)){
            return $this->response->setJson([
                'status' => 404,
                'message' => "Schedule Transaction is Not Found",
                'data' => []
            ], 404);
		}

		if(!empty($updateTrx)){
			$condition = "Finding";

			$trxModel->updateBatch($updateTrx, 'trxId');
		}

		$dataUpdate = [
			"approvedNotes" => $approvedNotes,
			"approvedAt" => $dateNow->format("Y-m-d H:i:s"),
			"approvedBy" => $_SESSION["username"] ?? "user01",
			"condition" => $condition
		];

		$scheduleTrxModel->update($scheduleTrxId, $dataUpdate);

		return $this->response->setJson([
			'status' => 200,
			'message' => "Transaction Approved Successfully",
			'data' => []
		], 200);
	}
}
