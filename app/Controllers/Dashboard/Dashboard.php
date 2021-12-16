<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;
use App\Models\TagModel;
use App\Models\TagLocationModel;
use Exception;

class Dashboard extends BaseController
{
	public function index()
	{
		if (!checkRoleList("DASHBOARD.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$assetModel = new AssetModel();
		$tagModel = new TagModel();
		$locationModel = new TagLocationModel();
		$scheduleTrxModel = new ScheduleTrxModel();
		$approvedAtNull = $scheduleTrxModel->where(['userId' => $this->session->get("adminId"), 'approvedAt', null])->from("vw_scheduleTrx")->get()->getResultArray();
		$approvedAtNotNull = $scheduleTrxModel->where(['userId' => $this->session->get("adminId"), 'approvedAt !=', null])->from("vw_scheduleTrx")->get()->getResultArray();
		$normal = $scheduleTrxModel->where(['userId' => $this->session->get("adminId"), 'condition', 'Normal'])->from("vw_scheduleTrx")->get()->getResultArray();
		$finding = $scheduleTrxModel->where(['userId' => $this->session->get("adminId"), 'condition', 'Finding'])->from("vw_scheduleTrx")->get()->getResultArray();
		$open = $scheduleTrxModel->where(['userId' => $this->session->get("adminId"), 'condition', 'Open'])->from("vw_scheduleTrx")->get()->getResultArray();
		$closed = $scheduleTrxModel->where(['userId' => $this->session->get("adminId"), 'condition', 'Closed'])->from("vw_scheduleTrx")->get()->getResultArray();
		$dataAsset = $assetModel->where(['userId' => $this->session->get("adminId"), 'deletedAt', null])->get()->getResultArray();
		$dataTag = $tagModel->where(['userId' => $this->session->get("adminId"), 'deletedAt', null])->get()->getResultArray();
		$dataLocation = $locationModel->where(['userId' => $this->session->get("adminId"), 'deletedAt', null])->get()->getResultArray();

		$data = array(
			'title' => "Dashboard",
			'subtitle' => 'Dashboard Equipment Record'
		);
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
		];
		$data['totalAsset'] = count($dataAsset);
		$data['totalTag'] = count($dataTag);
		$data['totalLocation'] = count($dataLocation);
		$data['approveNull'] = count($approvedAtNull);
		$data['approveNotNull'] = count($approvedAtNotNull);
		$data['normal'] = count($normal);
		$data['finding'] = count($finding);
		$data['open'] = count($open);
		$data['closed'] = count($closed);
		return $this->template->render('Dashboard/index', $data);
	}

	public function getTagTagLoc()
	{
		try {
			$tagModel = new TagModel();
			$tagLocModel = new TagLocationModel();

			$tagData = $tagModel->getAll(["userId" => $this->session->get("adminId")]);
			$tagLocData = $tagLocModel->getAll(["userId" => $this->session->get("adminId")]);

			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success Get Data',
				'data' => [
					'tagData' => $tagData,
					'tagLocationData' => $tagLocData
				]
			), 200);
		} catch (Exception $e){
			return $this->response->setJSON(array(
				'status' => 500,
				'message' => $e->getMessage(),
				'data' => []
			), 500);
		}
	}
}
