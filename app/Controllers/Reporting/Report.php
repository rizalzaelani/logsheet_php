<?php

namespace App\Controllers\Reporting;

use App\Controllers\BaseController;

class Report extends BaseController
{
	public function index()
	{
        if(!checkRoleList("REPORT.RAWDATA.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$data = array(
			'title' => 'Report',
			'subtitle' => 'Export Data Application IPC Logsheet'
		);

		return $this->template->render('Reporting/Report/index', $data);
	}
}
