<?php

namespace App\Controllers\Reporting;

use App\Controllers\BaseController;
use App\Models\TagLocationModel;
use App\Models\TagModel;

class Report extends BaseController
{
	public function index()
	{
        if(!checkRoleList("REPORT.RAWDATA.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$tagModel = new TagModel();
		$tagLocationModel = new TagLocationModel();
		$data = array(
			'title' => 'Report',
			'subtitle' => 'Export Data Application Logsheet Digital'
		);

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Reporting",
				"link"	=> "Report"
			],
		];

		$tag = $tagModel->getAll();
		$tagLocation = $tagLocationModel->getAll();

		$data['tag'] = $tag;
		$data['tagLocation'] = $tagLocation;

		return $this->template->render('Reporting/Report/index', $data);
	}

	public function getTransaction()
	{
		$this->db = db_connect();
		$post = $this->request->getPost();
		$userId = $post['userId'];
		$start = $post['start'];
		$end = $post['end'];
		$search = $post['search'];
		$tagId = $post['tagId'];
		$tagLocationId = $post['tagLocationId'];
		$transaction = $this->db->query("CALL sp_transaction(?, ?, ?, ?, ?, ?)", [$userId, $start, $end, $search, $tagId, $tagLocationId]);
		$data = $transaction->getResult();
		$gzip = gzencode(json_encode($data));
		header("Content-type: text/javascript");
		header('Content-Encoding: gzip');
		echo $gzip;
	}

	public function getFinding()
	{
		$this->db = db_connect();
		$post = $this->request->getPost();
		$userId = $post['userId'];
		$start = $post['start'];
		$end = $post['end'];
		$search = $post['search'];
		$tagId = $post['tagId'];
		$tagLocationId = $post['tagLocationId'];
		$finding = $this->db->query("CALL sp_finding(?, ?, ?, ?, ?, ?)", [$userId, $start, $end, $search, $tagId, $tagLocationId]);
		$data = $finding->getResult();
		$gzip = gzencode(json_encode($data));
		header("Content-type: text/javascript");
		header('Content-Encoding: gzip');
		echo $gzip;
	}

	public function getSchedule()
	{
		$this->db = db_connect();
		$post = $this->request->getPost();
		$userId = $post['userId'];
		$start = $post['start'];
		$end = $post['end'];
		$search = $post['search'];
		$tagId = $post['tagId'];
		$tagLocationId = $post['tagLocationId'];
		$shcedule = $this->db->query("CALL sp_schedule(?, ?, ?, ?, ?, ?)", [$userId, $start, $end, $search, $tagId, $tagLocationId]);
		$data = $shcedule->getResult();
		$gzip = gzencode(json_encode($data));
		header("Content-type: text/javascript");
		header("Content-Encoding: gzip");
		echo $gzip;
	}

	public function download()
	{
		$data = $this->request->getPost('data');
		var_dump(json_decode($data, true));
		die();
	}
}
