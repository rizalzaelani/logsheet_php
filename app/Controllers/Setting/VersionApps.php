<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;

class VersionApps extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Version Apps',
			'subtitle' => 'Versioning Mobile Application'
		);

		return $this->template->render('Setting/VersionApps/index', $data);
	}
}
