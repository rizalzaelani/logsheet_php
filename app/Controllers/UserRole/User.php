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
        // if(!checkRoleList("USER.VIEW")){
        //     return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        // }

		$data = array(
			'title' => 'User',
			'subtitle' => 'All User Logsheet'
		);

		return $this->template->render('UserRole/User/index', $data);
	}
}