<?php

namespace App\Controllers\Reporting;

use App\Controllers\BaseController;

class Asset extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Reporting Asset',
			'subtitle' => 'List Equipment IPC Logsheet'
		);
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Reporting Asset",
				"link"	=> "reportingAsset"
			],
		];
		return $this->template->render('Reporting/Asset/index.php', $data);
	}

	public function detail()
	{
		$json = file_get_contents('json/transactionParameter.json');
		$arr = json_decode($json);
		$data = array(
			'title' => 'Detail Reporting',
			'subtitle' => 'Detail Reporting',
		);
		$data['arr'] = $arr;
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Reporting Asset",
				"link"	=> "ReportingAsset"
			],
			[
				"title"	=> "Detail",
				"link"	=> ""
			],
		];
		return $this->template->render('Reporting/Asset/detail', $data);
	}
}
