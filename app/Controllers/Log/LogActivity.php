<?php

namespace App\Controllers\Log;

use App\Controllers\BaseController;

class LogActivity extends BaseController
{
	public function index()
	{
        if(!checkRoleList("LOGACTIVITY.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$data = array(
			'title' => 'Log Activity',
			'subtitle' => 'Log Activity Users'
		);

		return $this->template->render('LogActivity/index', $data);
	}
}
