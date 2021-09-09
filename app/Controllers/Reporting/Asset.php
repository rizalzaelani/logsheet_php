<?php

namespace App\Controllers\Reporting;

use App\Controllers\BaseController;
use DateTime;

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

	function coba($val)
	{
		return $val == 'bd1b9eca-71e4-489c-9e08-55776e8bcfb0';
	}
	public function detail()
	{
		$json = file_get_contents('json/transactionsParameter.json');
		$arr = json_decode($json);
		$dataParameter = $arr->dataParameter;
		$dataSchedule = $arr->dataSchedule;
		$dataRecord = $arr->dataRecord;
		$groupSch = array();
		foreach ($dataSchedule as $key) {
			$groupSch[date('d M Y', strtotime($key->scheduleFrom))][] = $key;
		}
		$data = array(
			'title' => 'Detail Reporting',
			'subtitle' => 'Detail Reporting',
		);
		$data['dataParameter'] = $dataParameter;
		$data['dataSchedule'] = $dataSchedule;
		$data['dataRecord'] = $dataRecord;
		$data['groupSch'] = $groupSch;
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

	public function tableDetail()
	{
		$json = file_get_contents('json/transactionsParameter.json');
		$arr = json_decode($json);
		$dataParameter = $arr->dataParameter;
		$dataSchedule = $arr->dataSchedule;
		$data = array(
			'dataParameter' => $dataParameter,
			'dataSchedule' => $dataSchedule,
		);
		echo json_encode($data);
	}
}
