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
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Version Apps",
				"link"	=> "VersionApps"
			],
		];
		return $this->template->render('Setting/VersionApps/index', $data);
	}
}
