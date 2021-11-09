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
        if(!checkRoleList("DASHBOARD.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        }
		
		$assetModel = new AssetModel();
		$tagModel = new TagModel();
		$locationModel = new TagLocationModel();
		$scheduleTrxModel = new ScheduleTrxModel();
		$approvedAtNull = $scheduleTrxModel->where('approvedAt', null)->get()->getResultArray();
		$approvedAtNotNull = $scheduleTrxModel->where('approvedAt !=', null)->get()->getResultArray();
		$normal = $scheduleTrxModel->where('condition', 'normal')->get()->getResultArray();
		$finding = $scheduleTrxModel->where('condition', 'finding')->get()->getResultArray();
		$open = $scheduleTrxModel->where('condition', 'open')->get()->getResultArray();
		$closed = $scheduleTrxModel->where('condition', 'closed')->get()->getResultArray();
		$dataAsset = $assetModel->where('deletedAt', null)->get()->getResultArray();
		$dataTag = $tagModel->findAll();
		$dataLocation = $locationModel->findAll();
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
