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
        if(!checkRoleList("REPORT.ASSET.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
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

	public function detail(){
        if(!checkRoleList("REPORT.ASSET.DETAIL")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
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
		if(empty($checkAsset)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data['assetData'] = $checkAsset;
		$data['parameterData'] = $parameterModel->getAll(["deletedAt IS NULL" => null, "assetId" => $assetId]);
		$data['scheduleData'] = $scheduleTrxModel->getAll(["assetId" => $assetId, "scheduleFrom >=" => $dateFrom, "scheduleFrom <" => date("Y-m-d 00:00:00", strtotime($dateTo . " +1 days"))]);

		$scheduleTrxIdArr = [];
		if(!empty($data['scheduleData'])){
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

        if(!checkRoleList("REPORT.ASSET.VIEW")){
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

		$filtTag = explode(",", $_POST["columns"][2]["search"]["value"] ?? '');
		$filtLoc = explode(",", $_POST["columns"][3]["search"]["value"] ?? '');
		$where = [
			'deletedAt' => null,
			// "(concat(',', tagName, ',') IN concat(',', " . $filtTag . ", ',') OR concat(',', tagLocationName, ',') IN concat(',', " . $filtLoc . ", ','))" => null
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
