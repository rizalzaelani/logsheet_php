<?php

namespace App\Controllers\Login;

use App\Controllers\BaseController;

class Login extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Login Page | Logsheet Digital',
			'subtitle' => 'Logsheet Digital'
		);

		return $this->template->render('Login/index', $data);
	}
    public function auth()
    {
        $post = $this->request->getPost();
        var_dump($post);
        die();
    }
}
