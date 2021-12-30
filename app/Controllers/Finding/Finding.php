<?php

namespace App\Controllers\Finding;

use App\Controllers\BaseController;
use App\Models\FindingLogModel;
use App\Models\FindingModel;
use App\Models\ScheduleTrxModel;
use App\Models\TransactionModel;
use App\Models\Influx\LogModel;

use DateTime;
use Exception;

class Finding extends BaseController
{
	public function index()
	{
		if (!checkRoleList("FINDING.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$data = array(
			'title' => 'Finding',
			'subtitle' => 'All Finding Logsheet'
		);

		return $this->template->render('Finding/index', $data);
	}

	public function detailList()
	{
		if (!checkRoleList("FINDING.DETAIL.LIST.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$scheduleTrxId = $this->request->getVar("scheduleTrxId") ?? "";

		if ($scheduleTrxId == "") {
			return View('errors/customError', ["errorCode" => 404, "errorMessage" => "Sorry, Page Requested Not Found", "returnLink" => site_url("Finding")]);
		}

		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();

		$getSchedule = $scheduleTrxModel->getById($scheduleTrxId);
		if (empty($getSchedule)) {
			return View('errors/customError', ["errorCode" => 404, "errorMessage" => "Sorry, Page Requested Not Found", "returnLink" => site_url("Finding")]);
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
		// $influxModel = new LogModel();
        // $from = new DateTime();
        // $to = new DateTime();
        // $dateFrom = $from->format("Y-m-d H:i:s");
        // $dateTo = $from->modify("+1 days")->format("Y-m-d H:i:s");
        // $test = $influxModel->getAll($dateFrom, $dateFrom);
        // d($this->request->getUserAgent());
        // d($test);
        // die();
		if (!checkRoleList("FINDING.DETAIL.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$data = array(
			'title' => 'Detail Finding',
			'subtitle' => 'Detail Finding'
		);

		$findingModel = new FindingModel();
		$findingId = $this->request->getVar('findingId');

		$checkFinding = $findingModel->getById($findingId);
		if (empty($checkFinding)) {
			return View('errors/customError', ["errorCode" => 404, "errorMessage" => "Sorry, Page Requested Not Found", "returnLink" => $_SERVER['HTTP_REFERER']]);
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

		if (!checkRoleList("FINDING.VIEW")) {
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

		if ($filtTag != '') $where["find_in_set_multiple('$filtTag', tagId)"] = null;
		if ($filtLoc != '') $where["find_in_set_multiple('$filtLoc', tagLocationId)"] = null;
		if ($filtCond != '') $where["condition"] = $filtCond;

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
		if (!checkRoleList("FINDING.OPEN")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();
		$findingModel = new FindingModel();
		$findingLogModel = new FindingLogModel();

		$dateNow = new DateTime();

		$trxId = $this->request->getVar('trxId') ?? '';

		$checkTrx = $trxModel->where('trxId', $trxId)->get()->getRowArray();
		if (empty($checkTrx)) {
			return View('errors/customError', ["errorCode" => 404, "errorMessage" => "Sorry, Page Requested Not Found", "returnLink" => $_SERVER['HTTP_REFERER']]);
		}

		$sendNotif = false;
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
				$sendNotif = true;
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

			$dataUpdated = $scheduleTrxModel->getById($checkTrx["scheduleTrxId"]);
			if ($this->isJson($dataUpdated['description'])) {
				$dataUpdated['description'] = json_decode($dataUpdated['description']);
			}

			$assetId = $dataUpdated['assetId'];
			$activity	= 'Open finding';
			sendLog($activity, $assetId, json_encode($dataUpdated));

			if($sendNotif){
				$checkFinding = $findingModel->getById($findingId);
				$this->sendMail($findingId, "Follow Up Finding", $dateNow->format("Y-m-d H:i:s"), $this->session->get("name"), $checkFinding);
				$this->sendTelegram($findingId, "open", $this->session->get("name"), $dateNow->format("Y-m-d H:i:s"), $checkFinding["parameterName"]);
			}

			

			return redirect()->to("/Finding/detail?findingId=" . $findingId);
		} catch (Exception $e) {
			throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function closeFinding()
	{
		if (!checkRoleList("FINDING.CLOSE")) {
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

		$checkFinding = $findingModel->getById($findingId);
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

			$findingLogModel = new FindingLogModel();
			$findingLogData = $findingLogModel->where("findingId", $findingId)->orderBy("createdAt", "asc")->get()->getResultArray();

			$dataUpdated = $scheduleTrxModel->getById($checkTrx["scheduleTrxId"]);
			if ($this->isJson($dataUpdated['description'])) {
				$dataUpdated['description'] = json_decode($dataUpdated['description']);
			}
			$assetId = $dataUpdated['assetId'];
			$activity	= 'Close finding';
			sendLog($activity, $assetId, json_encode($dataUpdated));

			$this->sendMail($findingId, "Close Finding", $dateNow->format("Y-m-d H:i:s"), $this->session->get("name"), $checkFinding, $findingLogData);
			$this->sendTelegram($findingId, "close", $this->session->get("name"), $dateNow->format("Y-m-d H:i:s"), $checkFinding["parameterName"]);

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
		if (!checkRoleList("FINDING.LOG.LIST")) {
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
		if (!checkRoleList("FINDING.LOG.ADD")) {
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
		$scheduleTrxModel = new ScheduleTrxModel();

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
			$dataFinding = $findingModel->getById($findingId);
			$dataUpdated = $scheduleTrxModel->getById($dataFinding['scheduleTrxId']);
			$assetId = $dataUpdated['assetId'];
			if ($this->isJson($dataUpdated['description'])) {
				$dataUpdated['description'] = json_decode($dataUpdated['description']);
			}
			$dataInflux = json_encode($dataUpdated);

			$activity	= 'Add timeline';
			sendLog($activity, $assetId, $dataInflux);

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

	private function sendMail($findingId, $title, $dateTime, $by, array $dt, array $dtTimeline = [])
	{
		try {
			$type = (strpos(strtolower($title), 'close') ? 'Close' : 'Follow Up');
			$notifModel = new \App\Models\NotificationModel();
			$mailToArr = $notifModel->getAll(["deletedAt IS NULL" => null, "status" => "active", "type" => "email", "FIND_IN_SET('" . ($type == "Close" ? "Closed" : "Open") . " Finding', `trigger`) > 0" => null]);
			if (!empty($mailToArr)) {
				$link = site_url("/Finding/detail?findingId=" . $findingId);
				$message = file_get_contents(base_url() . "/assets/Mail/finding.txt");

				$message = str_replace("{{linkFinding}}", $link, $message);
				$message = str_replace("{{title}}", $title, $message);
				$message = str_replace("{{type}}", $type, $message);
				$message = str_replace("{{dateTime}}", date("Y M d H:i:s", strtotime($dateTime)), $message);
				$message = str_replace("{{by}}", $by, $message);

				$message = str_replace("{{assetName}}", $dt['assetName'], $message);
				$message = str_replace("{{assetNumber}}", $dt['assetNumber'], $message);
				$message = str_replace("{{parameterName}}", $dt['parameterName'], $message);
				$message = str_replace("{{description}}", $dt['description'], $message);
				$message = str_replace("{{uom}}", $dt['uom'], $message);
				$message = str_replace("{{value}}", $dt['value'], $message);

				$tag = '';
				if ($dt['tagName'] != '-') {
					$assetTagValue = (array_values(array_unique(explode(",", $dt['tagName']))));
					$length = count($assetTagValue);
					for ($i = 0; $i < $length; $i++) {
						$tag .= '<span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">' . $assetTagValue[$i] . '</span>';
					}
				} else {
					$tag = "-";
				}

				$location = '';
				if ($dt['tagLocationName'] != '-') {
					$assetTagValue = (array_values(array_unique(explode(",", $dt['tagLocationName']))));
					$length = count($assetTagValue);
					for ($i = 0; $i < $length; $i++) {
						$location .= '<span class="badge badge-primary p-1 mr-1" style="font-size: 13px;">' . $assetTagValue[$i] . '</span>';
					}
				} else {
					$location = "-";
				}

				$message = str_replace("{{tag}}", $tag, $message);
				$message = str_replace("{{location}}", $location, $message);

				$normal = '';
				$abnormal = '';
				if (empty($dt["option"])) {
					$normal = (empty($dt["min"]) && empty($dt["max"]) ? "(Any)" : (empty($dt["min"]) ? ('<' . $dt["max"]) : (empty($dt["max"]) ? ('>' . $dt["min"]) : ($dt["min"] . ' - ' . $dt["max"]))));
					$abnormal = (empty($dt["min"]) && empty($dt["max"]) ? "(Any)" : ((!empty($dt["min"]) ? ('x < ' . $dt["min"] . '; ') : '') . (!empty($dt["max"]) ? ('x > ' . $dt["max"]) : '')));
				} else {
					$normal = empty($dt["normal"]) ? "(Empty)" : $dt["normal"];
					$abnormal = empty($dt["abnormal"]) ? "(Empty)" : $dt["abnormal"];
				}

				$message = str_replace("{{normal}}", $normal, $message);
				$message = str_replace("{{abnormal}}", $abnormal, $message);

				if (!empty($dtTimeline)) {
					$msgTL = file_get_contents(base_url() . "/assets/Mail/findingTimeline.txt");

					$listTL = "";
					foreach ($dtTimeline as $key => $row) {
						$dateTL = date("Y M d H:i:s", strtotime($row['createdAt']));
						$colorTL = "tl-item dot-" . ($key == 0 ? "primary" : (($key + 1) == count($dtTimeline) ? "danger" : "success"));
						$listTL .= <<<HTML
							<li class="$colorTL" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial,'helvetica neue',helvetica,sans-serif;line-height:21px;Margin-bottom:15px;color:#333;font-size:14px;list-style:none;margin:auto;margin-left:40px;min-height:50px;padding:0 0 30 30;position:relative">
								<div class="item-detail" style="color:#7f7f7f;font-size:12px">$dateTL</div>
								<div class="item-title font-weight-bold mb-2">{$row["createdBy"]}</div>
								<div class="item-notes" style="color:#7f7f7f;font-size:12.5px">{$row["notes"]}</div>
							</li>
						HTML;
					}

					$msgTL = str_replace("{{listTimeline}}", $listTL, $msgTL);
					$message = str_replace("{{findingTimeline}}", $msgTL, $message);
				} else {
					$message = str_replace("{{findingTimeline}}", "", $message);
				}

				// send mail
				$mailService = \Config\Services::email();

				$mailService->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
				$mailService->setTo(implode(",", array_column($mailToArr, "value")));
				$mailService->setSubject($title . " " . $dt['parameterName']);
				$mailService->setMessage($message);
				$mailService->setMailType("html");

				$mailService->send();

				return implode(array_column($mailToArr, "value"));
			} else {
				return "fail";
			}
		} catch (Exception $e) {
			return $e;
		}
	}

	private function sendTelegram($findingId, $type, $by, $dateTime, $parameterName)
	{
		try {
			$notifModel = new \App\Models\NotificationModel();
			$adminId = $this->session->get("adminId");
			$dtNotif = $notifModel->getAll(["userId" => $adminId,"deletedAt IS NULL" => null, "status" => "active", "type" => "telegram", "FIND_IN_SET('" . ($type == "Close" ? "Closed" : "Open") . " Finding', `trigger`) > 0" => null]);
			if (!empty($dtNotif)) {
				$bot = new \TelegramBot\Api\BotApi(env('botTelegramToken'));
				foreach($dtNotif as $row){
					try {
						$bot->sendMessage($row["value"], $by . ($type == "close" ? " Closed " : " Follow Up New ") . "Finding " . $parameterName . " at " . date("Y M d H:i:s", strtotime($dateTime)) . "\n\nMore Details : " . site_url("/Finding/detail?findingId=" . $findingId));
					} catch (Exception $er){
						continue;
					}
				}

				return "success";
			}
		} catch (Exception $e) {
			return $e;
		}
	}

	private function isJson(string $value)
	{
		try {
			json_decode($value, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $e) {
			return false;
		}
	
		return true;
	}
}
