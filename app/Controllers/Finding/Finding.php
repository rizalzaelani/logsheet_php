<?php

namespace App\Controllers\Finding;

use App\Controllers\BaseController;

class Finding extends BaseController
{
	public function index()
	{
		$data = array(
			'title' => 'Finding',
			'subtitle' => 'All Finding IPC Logsheet'
		);

		return $this->template->render('Finding/index', $data);
	}

	public function detailList()
	{
		$data = array(
			'title' => 'Detail Finding',
			'subtitle' => 'Detail Finding'
		);

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Finding",
				"link"	=> "Finding"
			],
			[
				"title"	=> "Detail List",
				"link"	=> ""
			],
		];
		return $this->template->render('Finding/detailList', $data);
	}

	public function detail()
	{
		$data = array(
			'title' => 'Detail Finding',
			'subtitle' => 'Detail Finding'
		);

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Finding",
				"link"	=> "Finding"
			],
			[
				"title"	=> "Detail List",
				"link"	=> site_url("Finding/detailList?trxId=1")
			],
			[
				"title"	=> "Detail",
				"link"	=> ""
			],
		];
		return $this->template->render('Finding/detail', $data);
	}
}
