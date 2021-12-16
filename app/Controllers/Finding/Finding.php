<?php

namespace App\Controllers\Finding;

use App\Controllers\BaseController;
use App\Models\FindingLogModel;
use App\Models\FindingModel;
use App\Models\ScheduleTrxModel;
use App\Models\TransactionModel;
use DateTime;
use Exception;

class Finding extends BaseController
{
	public function index()
	{
        if(!checkRoleList("FINDING.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$data = array(
			'title' => 'Finding',
			'subtitle' => 'All Finding Logsheet'
		);

		return $this->template->render('Finding/index', $data);
	}

	public function detailList()
	{
        if(!checkRoleList("FINDING.DETAIL.LIST.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$scheduleTrxId = $this->request->getVar("scheduleTrxId") ?? "";

		if($scheduleTrxId == ""){
            return View('errors/customError', ["errorCode"=>404,"errorMessage"=>"Sorry, Page Requested Not Found","returnLink"=>site_url("Finding")]);
		}

		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();

		$getSchedule = $scheduleTrxModel->getById($scheduleTrxId);
		if(empty($getSchedule)){
            return View('errors/customError', ["errorCode"=>404,"errorMessage"=>"Sorry, Page Requested Not Found","returnLink"=>site_url("Finding")]);
		}

		$data["scheduleTrxData"] = $getSchedule;
		$data["trxData"] = $trxModel->getAll(["scheduleTrxId" => $scheduleTrxId, "condition !=" => "Normal"]);

		$data['title'] = 'Detail Finding';
		$data['subtitle'] = 'Detail Finding';

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Finding",
				"link"	=> "Finding"
			],
			[
				"title"	=> "Detail List",
				"link"	=> ""
			],
		];
		return $this->template->render('Finding/detailList', $data);
	}

	public function detail()
	{
        if(!checkRoleList("FINDING.DETAIL.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$data = array(
			'title' => 'Detail Finding',
			'subtitle' => 'Detail Finding'
		);

		$findingModel = new FindingModel();
		$findingId = $this->request->getVar('findingId');

		$checkFinding = $findingModel->getById($findingId);
		if (empty($checkFinding)) {
            return View('errors/customError', ["errorCode"=>404,"errorMessage"=>"Sorry, Page Requested Not Found","returnLink"=>$_SERVER['HTTP_REFERER']]);
		}

		$data["findingData"] = $checkFinding;
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Finding",
				"link"	=> "Finding"
			],
			[
				"title"	=> "Detail List",
				"link"	=> site_url("Finding/detailList?trxId=1")
			],
			[
				"title"	=> "Detail",
				"link"	=> ""
			],
		];
		return $this->template->render('Finding/detail', $data);
	}

	public function datatable()
	{
		$request = \Config\Services::request();

        if(!checkRoleList("FINDING.VIEW")){
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
		$column_order = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'condition', 'scheduleTrxId');
		$column_search = array('scheduleFrom', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'condition', 'scheduleTrxId');
		$order = array('createdAt' => 'asc');
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
		$where = [
			'userId' => $this->session->get("adminId"),
			'scannedAt IS NOT NULL' => null,
			'approvedAt IS NOT NULL' => null,
			'condition !=' => 'Normal'
		];

		$filtTag = $_POST["columns"][3]["search"]["value"] ?? '';
		$filtLoc = $_POST["columns"][4]["search"]["value"] ?? '';
		$filtCond = $_POST["columns"][0]["search"]["value"] ?? '';
		
		if($filtTag != '') $where["find_in_set_multiple('$filtTag', tagId)"] = null;
		if($filtLoc != '') $where["find_in_set_multiple('$filtLoc', tagLocationId)"] = null;
		if($filtCond != '') $where["condition"] = $filtCond;

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

	public function issue()
	{
        if(!checkRoleList("FINDING.OPEN")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();
		$findingModel = new FindingModel();
		$findingLogModel = new FindingLogModel();

		$dateNow = new DateTime();

		$trxId = $this->request->getVar('trxId') ?? '';

		$checkTrx = $trxModel->where('trxId', $trxId)->get()->getRowArray();
		if (empty($checkTrx)) {
            return View('errors/customError', ["errorCode"=>404,"errorMessage"=>"Sorry, Page Requested Not Found","returnLink"=>$_SERVER['HTTP_REFERER']]);
		}

		try {
			$checkFinding = $findingModel->where("trxId", $trxId)->get()->getRowArray();
			$findingId = uuidv4();
			$condition = "Open";
			if (empty($checkFinding)) {
				$dtInsertFinding = array(
					"findingId" => $findingId,
					"trxId" => $checkTrx["trxId"],
					"condition" => "Open",
					"openedAt" => $dateNow->format("Y-m-d H:i:s"),
					"openedBy" => $this->session->get("name"),
					"findingPriority" => "Low"
				);
				
				$dataInsertLog = array(
					"findingLogId" => null,
					"findingId" => $findingId,
					"notes" => "Finding Opened By " . ($this->session->get("name")),
					"createdBy" => $this->session->get("name")
				);
				
				$findingModel->insert($dtInsertFinding);
				$findingLogModel->insert($dataInsertLog);
				
			} else {
				$findingId = $checkFinding["findingId"];
				$condition = $checkFinding["condition"];
			}

			$trxModel->update($checkTrx["trxId"], ["condition" => $condition]);

			$getCondAllTrx = $trxModel->where("scheduleTrxId", $checkTrx["scheduleTrxId"])->select("condition")->distinct()->get()->getResultArray();
			$scheduleCond = "Normal";
			if (!empty(array_filter($getCondAllTrx, function ($val) {
				return $val["condition"] == "Finding";
			}))) {
				$scheduleCond = "Finding";
			} else if (!empty(array_filter($getCondAllTrx, function ($val) {
				return $val["condition"] == "Open";
			}))) {
				$scheduleCond = "Open";
			} else if (!empty(array_filter($getCondAllTrx, function ($val) {
				return $val["condition"] == "Closed";
			}))) {
				$scheduleCond = "Closed";
			}

			$scheduleTrxModel->update($checkTrx["scheduleTrxId"], ["condition" => $scheduleCond]);

			return redirect()->to("/Finding/detail?findingId=" . $findingId);
		} catch (Exception $e) {
			throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function closeFinding()
	{
        if(!checkRoleList("FINDING.CLOSE")){
			return $this->response->setJSON([
				'status' => 403,
                'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
        }

		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();
		$findingModel = new FindingModel();
		$findingLogModel = new FindingLogModel();

		$dateNow = new DateTime();

		$findingId = $this->request->getVar('findingId') ?? '';

		$checkFinding = $findingModel->where("findingId", $findingId)->get()->getRowArray();
		if (empty($checkFinding)) {
			return $this->response->setJSON([
				'status' => 404,
				'message' => "Data Finding Not Found",
				'data' => []
			], 404);
		}

		try {
			$dtUpdateFinding = array(
				"closedAt" => $dateNow->format("Y-m-d H:i:s"),
				"closedBy" => $this->session->get("name"),
				"condition" => "Closed"
			);
				
			$dataInsertLog = array(
				"findingLogId" => null,
				"findingId" => $findingId,
				"notes" => "Finding Closed By " . ($this->session->get("name")),
				"createdBy" => $this->session->get("name")
			);
			
			$findingModel->update($findingId, $dtUpdateFinding);
			$findingLogModel->insert($dataInsertLog);

			$checkTrx = $trxModel->find($checkFinding["trxId"]);
			if (empty($checkTrx)) {
				return $this->response->setJSON([
					'status' => 404,
					'message' => "Data Transaction Not Found",
					'data' => []
				], 404);
			}

			$trxModel->update($checkFinding["trxId"], ["condition" => "Closed"]);

			$getCondAllTrx = $trxModel->where("scheduleTrxId", $checkTrx["scheduleTrxId"])->select("condition")->distinct()->get()->getResultArray();
			$scheduleCond = "Normal";
			if (!empty(array_filter($getCondAllTrx, function ($val) {
				return $val["condition"] == "Finding";
			}))) {
				$scheduleCond = "Finding";
			} else if (!empty(array_filter($getCondAllTrx, function ($val) {
				return $val["condition"] == "Open";
			}))) {
				$scheduleCond = "Open";
			} else if (!empty(array_filter($getCondAllTrx, function ($val) {
				return $val["condition"] == "Closed";
			}))) {
				$scheduleCond = "Closed";
			}

			$scheduleTrxModel->update($checkTrx["scheduleTrxId"], ["condition" => $scheduleCond]);

			return $this->response->setJSON([
				'status' => 200,
				'message' => "Finding Successfully Closed",
				'data' => []
			], 200);
		} catch (Exception $e) {
			return $this->response->setJSON([
				'status' => 500,
				'message' => $e->getMessage(),
				'data' => []
			], 500);
		}
	}

	public function getFindingLog()
	{
        if(!checkRoleList("FINDING.LOG.LIST")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

		$isValid = $this->validate([
			'findingId' => 'required',
		]);

		if (!$isValid) {
			return $this->response->setJSON([
				'status' => 400,
				'message' => $this->validator->getErrors(),
				'data' => []
			], 400);
		}

		$findingLogModel = new FindingLogModel();

		$findingLogData = $findingLogModel->where("findingId", $this->request->getVar("findingId"))->orderBy("createdAt", "asc")->get()->getResultArray();

		return $this->response->setJSON([
			'status' => 200,
			'message' => "success get data",
			'data' => $findingLogData
		], 200);
	}

	public function addFindingLog()
	{
        if(!checkRoleList("FINDING.LOG.ADD")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

		$isValid = $this->validate([
			'findingId' => 'required',
			'notes' => 'required',
		]);

		if (!$isValid) {
			return $this->response->setJSON([
				'status' => 400,
				'message' => $this->validator->getErrors(),
				'data' => []
			], 400);
		}

		$findingId = $this->request->getVar("findingId");
		$notes = $this->request->getVar("notes");

		$findingModel = new FindingModel();
		$findingLogModel = new FindingLogModel();

		$checkFinding = $findingModel->find($findingId);
		if (empty($checkFinding)) {
			return $this->response->setJSON([
				'status' => 404,
				'message' => "Finding Data is Not Found",
				'data' => []
			], 404);
		}

		$dataInsert = array(
			"findingLogId" => null,
			"findingId" => $findingId,
			"notes" => $notes,
			"createdBy" => $this->session->get("name")
		);

		try {
			$findingLogModel->insert($dataInsert);

			return $this->response->setJSON([
				'status' => 200,
				'message' => "success get data",
				'data' => $dataInsert
			], 200);
		} catch (Exception $e) {
			return $this->response->setJSON([
				'status' => 500,
				'message' => $e->getMessage(),
				'data' => []
			]);
		}
	}
}
