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
        if(!checkRoleList("TRX.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

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
        if(!checkRoleList("TRX.DETAIL.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$scheduleTrxId = $this->request->getVar("scheduleTrxId") ?? "";

		if($scheduleTrxId == ""){
            return View('errors/customError', ["errorCode"=>404,"errorMessage"=>"Sorry, Page Requested Not Found","returnLink"=>site_url("Transaction")]);
		}

		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();
		$attachmentTrxModel = new AttachmentTrxModel();

		$getSchedule = $scheduleTrxModel->getById($scheduleTrxId);
		if(empty($getSchedule)){
            return View('errors/customError', ["errorCode"=>404,"errorMessage"=>"Sorry, Page Requested Not Found","returnLink"=>site_url("Transaction")]);
		}

		$data["scheduleTrxData"] = $getSchedule;
		$data["trxData"] = $trxModel->getAll(["scheduleTrxId" => $scheduleTrxId]);
		$data["attachmentTrxData"] = $attachmentTrxModel->where(["scheduleTrxId" => $scheduleTrxId])->get()->getResultArray();

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
		$request = \Config\Services::request();

        if(!checkRoleList("TRX.VIEW")){
			echo json_encode(array(
				"draw" => $request->getPost('draw'),
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => [],
				'status' => 403,
				'message' => "You don't have access to this page"
			));
        }

		$table = 'vw_scheduleTrx';
		$column_order = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'approvedAt', 'scheduleTrxId');
		$column_search = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'approvedAt', 'scheduleTrxId');
		$order = array('createdAt' => 'asc');
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
		$where = [
			'userId' => $this->session->get("adminId"),
			'scannedAt IS NOT NULL' => null
		];

		$filtTag = $_POST["columns"][3]["search"]["value"] ?? '';
		$filtLoc = $_POST["columns"][4]["search"]["value"] ?? '';
		$filtStatus = $_POST["columns"][5]["search"]["value"] == '' ? 2 : $_POST["columns"][5]["search"]["value"];
		
		if($filtTag != '') $where["find_in_set_multiple('$filtTag', tagName)"] = null;
		if($filtLoc != '') $where["find_in_set_multiple('$filtLoc', tagLocationName)"] = null;
		if($filtStatus == 0 || $filtStatus == 1) $where["approvedAt IS " . ($filtStatus == 1 ? 'NOT ' : '') . "NULL"] = null;

		$list = $DTModel->datatable($where);
		$output = array(
			"draw" => $request->getPost('draw'),
			"recordsTotal" => $DTModel->count_all($where),
			"recordsFiltered" => $DTModel->count_filtered($where),
			"data" => $list,
			'status' => 200,
			'message' => 'success',
			's' => $filtStatus
		);
		echo json_encode($output);
	}

	public function approveTrx(){
        if(!checkRoleList("TRX.APPROVE")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
		}

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
		$updateTrx = [];
		if(!empty($getNormalAbnormal)){
			$trxData = $trxModel->getAll(["scheduleTrxId" => $scheduleTrxId]);
			foreach($trxData as $row){
				foreach($getNormalAbnormal as $val){
					if($val["abnormal"]	== 1 && $val["trxId"] == $row["trxId"]){
						array_push($updateTrx, array(
							"trxId" => $val["trxId"],
							"condition" => "Finding" 
						));
					}
				}
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
			'data' => [
				'updateTrx' => $updateTrx,
				'getNormalAbnormal' => $getNormalAbnormal
			]
		], 200);
	}
}
