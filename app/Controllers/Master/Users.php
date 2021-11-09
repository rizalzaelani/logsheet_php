<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;

class Users extends BaseController
{
	public function index()
	{
        if(!checkRoleList("MASTER.USER.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$data = array(
			'title' => 'List Users Logsheet',
			'subtitle' => 'List Users'
		);

		return $this->template->render('Users/index', $data);
	}
}
