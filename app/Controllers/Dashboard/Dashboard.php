<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;
use App\Models\TagModel;
use App\Models\TagLocationModel;

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

		$adminId = $this->session->get('adminId');

		$approvedAtNull = $scheduleTrxModel->getAll(['userId' => $adminId, 'approvedAt' => null]);
		$approvedAtNotNull = $scheduleTrxModel->getAll(['userId' => $adminId, 'approvedAt !=' => null]);
		$normal = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Normal']);
		$finding = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Finding']);
		$open = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Open']);
		$closed = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Closed']);
		$dataAsset = $assetModel->getAll(['userId' => $adminId, 'deletedAt' => null]);
		$dataTag = $tagModel->getAll(['userId' => $adminId]);
		$dataLocation = $locationModel->getAll(['userId' => $adminId]);

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
}
