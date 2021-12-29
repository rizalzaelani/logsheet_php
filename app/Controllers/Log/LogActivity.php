<?php

namespace App\Controllers\Log;

use App\Controllers\BaseController;
use App\Models\Influx\LogModel;
use Exception;

class LogActivity extends BaseController
{
	public function index()
	{
		if (!checkRoleList("LOGACTIVITY.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$data = array(
			'title' => 'Log Activity',
			'subtitle' => 'Log Activity Users'
		);

		$data["breadcrumbs"] = [
			[
				"title"    => "Home",
				"link"    => "Dashboard"
			],
			[
				"title"    => "Log Activity",
				"link"    => "LogActivity"
			],
		];

		return $this->template->render('LogActivity/index', $data);
	}

	public function getLogActivity()
	{
		// if(!checkRoleList("ROLE.VIEW")){
		// 	return $this->response->setJSON([
		// 		'status' => 403,
		//         'message' => "Sorry, You don't have access",
		// 		'data' => []
		// 	], 403);
		// }

		try {
			$userId = $this->session->get('adminId');
			$logModel = new LogModel();
			$logActivity = $logModel->getAll($userId);

			if (empty($logActivity)) {
				return $this->response->setJSON(array(
					'status' => 500,
					'message' => 'Bad Request!',
					'data' => $logActivity
				), 500);
			} else {
				return $this->response->setJSON([
					'status' => 200,
					'message' => "Success Get Log Activity",
					'data' => $logActivity ?? []
				], 200);
			}
		} catch (Exception $e) {
			return $this->response->setJSON([
				'status' => 500,
				'message' => $e->getMessage(),
				'data' => $e
			], 500);
		}
	}
}
