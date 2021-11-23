<?php

namespace App\Controllers\UserRole;

use App\Controllers\BaseController;

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