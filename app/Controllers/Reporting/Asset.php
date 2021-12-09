<?php

namespace App\Controllers\Reporting;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ParameterModel;
use App\Models\ScheduleTrxModel;
use App\Models\TagLocationModel;
use App\Models\TagModel;
use App\Models\TransactionModel;

class Asset extends BaseController
{
	public function index()
	{
		if (!checkRoleList("REPORT.ASSET.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$assetModel			= new AssetModel();
		$tagModel			= new TagModel();
		$tagLocationModel	= new TagLocationModel();

		$asset			= $assetModel->findColumn('assetName') ?? [];
		$tag			= $tagModel->findColumn('tagName') ?? [];
		$tagLocation	= $tagLocationModel->findColumn('tagLocationName') ?? [];

		$data['asset']			= $asset;
		$data['tag']			= $tag;
		$data['tagLocation']	= $tagLocation;

		$data['title'] = 'Reporting Asset';
		$data['subtitle'] = 'List Equipment';
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Reporting Asset",
				"link"	=> "reportingAsset"
			],
		];
		return $this->template->render('Reporting/Asset/index.php', $data);
	}

	public function detail()
	{
		if (!checkRoleList("REPORT.ASSET.DETAIL")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$assetModel = new AssetModel();
		$parameterModel = new ParameterModel();
		$scheduleTrxModel = new ScheduleTrxModel();
		$trxModel = new TransactionModel();

		// $dateNow = new DateTime();

		$assetId = $this->request->getVar("assetId") ?? "";
		$dateFrom = $this->request->getVar("dateFrom") ?? date("Y-m-d", strtotime("-6 days"));
		$dateTo = $this->request->getVar("dateTo") ?? date("Y-m-d 00:00:00");

		$checkAsset = $assetModel->getById($assetId);
		if (empty($checkAsset)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data['assetData'] = $checkAsset;
		$data['parameterData'] = $parameterModel->getAll(["deletedAt IS NULL" => null, "assetId" => $assetId]);
		$data['scheduleData'] = $scheduleTrxModel->getAll(["assetId" => $assetId, "scheduleFrom >=" => $dateFrom, "scheduleFrom <" => date("Y-m-d 00:00:00", strtotime($dateTo . " +1 days"))]);

		$scheduleTrxIdArr = [];
		if (!empty($data['scheduleData'])) {
			$scheduleTrxIdArr = array_column($data['scheduleData'], 'scheduleTrxId');
			$data["schId"] = $scheduleTrxIdArr;
			$data["trxData"] = $trxModel->getBySchIdIn($scheduleTrxIdArr);
		} else {
			$data["trxData"] = [];
		}

		$data["dateFrom"] = $dateFrom;
		$data["dateTo"] = $dateTo;

		$data['title'] = 'Detail Reporting';
		$data['subtitle'] = 'Detail Reporting';
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Reporting Asset",
				"link"	=> "ReportingAsset"
			],
			[
				"title"	=> "Detail",
				"link"	=> ""
			],
		];
		return $this->template->render('Reporting/Asset/detail', $data);
	}

	public function datatable()
	{
		$request = \Config\Services::request();

		if (!checkRoleList("REPORT.ASSET.VIEW")) {
			echo json_encode(array(
				"draw" => $request->getPost('draw'),
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => [],
				'status' => 403,
				'message' => "You don't have access to this page"
			));
		}

		$table = 'vw_asset';
		$column_order = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
		$column_search = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
		$order = array('createdAt' => 'asc');
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
		$where = [
			'userId' => $this->session->get("adminId"),
			'deletedAt' => null,
		];

		$filtTag = $_POST["columns"][1]["search"]["value"] ?? '';
		$filtLoc = $_POST["columns"][2]["search"]["value"] ?? '';
		
		if($filtTag != '') $where["find_in_set_multiple('$filtTag', tagName)"] = null;
		if($filtLoc != '') $where["find_in_set_multiple('$filtLoc', tagLocationName)"] = null;

		$list = $DTModel->datatable($where);
		$output = array(
			"draw" => $request->getPost('draw'),
			// "recordsTotal" => $DTModel->count_all($where),
			// "recordsFiltered" => $DTModel->count_filtered($where),
			"data" => $list,
			'status' => 200,
			'message' => 'success',
			's1' => $where,
		);
		echo json_encode($output);
	}

	public function getRecordByParam()
	{
		// if (!checkRoleList("REPORT.ASSET.TREND.VIEW")) {
		// 	return $this->response->setJSON([
		// 		'status' => 403,
		// 		'message' => "Sorry, You don't have access",
		// 		'data' => []
		// 	], 403);
		// }

		$isValid = $this->validate([
			'parameterId' => 'required'
		]);

		if (!$isValid) {
			return $this->response->setJson([
				'status' => 400,
				'message' => "Data is Not Valid",
				'data' => $this->validator->getErrors()
			], 400);
		}

		$parameterId = $this->request->getVar("parameterId") ?? "";
		$dateFrom = $this->request->getVar("dateFrom") ?? date("Y-m-d");
		$dateTo = $this->request->getVar("dateTo") ?? date("Y-m-d");

		$assetModel = new AssetModel();
		$data = $assetModel->getRecordTrendParam($parameterId, $dateFrom, $dateTo);

		return $this->response->setJSON([
			'status' => 200,
			'message' => "Success Get Record Trend Parameter",
			'data' => $data
		], 200);
	}
}
