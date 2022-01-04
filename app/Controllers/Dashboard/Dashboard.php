<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\Influx\LogModel;
use App\Models\ParameterModel;
use App\Models\ScheduleTrxModel;
use App\Models\TagModel;
use App\Models\TagLocationModel;
use App\Models\Wizard\SubscriptionModel;
use App\Models\Wizard\TransactionModel;
use DateTime;
use Exception;

class Dashboard extends BaseController
{
	public function index()
	{
		if (!checkRoleList("DASHBOARD.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$assetModel			= new AssetModel();
		$tagModel			= new TagModel();
		$locationModel		= new TagLocationModel();
		$scheduleTrxModel	= new ScheduleTrxModel();
		$subscriptionModel	= new SubscriptionModel();
		$parameterModel		= new ParameterModel();

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
		$dataParameter = $parameterModel->getAll(['userId' => $adminId]);

		$dataSubscription = $subscriptionModel->getAll(['userId' => $adminId]);
		$subscription = "";
		if (!empty($dataSubscription)) {
			$subscription = $dataSubscription[0];
		}

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
		$data['totalAsset']		= count($dataAsset);
		$data['totalTag']		= count($dataTag);
		$data['totalLocation']	= count($dataLocation);
		$data['approveNull']	= count($approvedAtNull);
		$data['approveNotNull'] = count($approvedAtNotNull);
		$data['normal']			= count($normal);
		$data['finding']		= count($finding);
		$data['open']			= count($open);
		$data['closed']			= count($closed);

		$data['availableAsset']		= $subscription['assetMax'] - count($dataAsset);
		$data['availableParameter'] = $subscription['parameterMax'] - count($dataParameter);
		$data['availableTag']		= $subscription['tagMax'] - count($dataTag);
		$data['availableTagLocation']= $subscription['tagMax'] - count($dataLocation);

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
		} catch (Exception $e) {
			return $this->response->setJSON(array(
				'status' => 500,
				'message' => $e->getMessage(),
				'data' => []
			), 500);
		}
	}
}
