<?php

namespace App\Controllers\Finding;

use App\Controllers\BaseController;
use App\Models\FindingLogModel;
use App\Models\FindingModel;
use App\Models\TransactionModel;
use DateTime;
use Exception;

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

		$findingModel = new FindingModel();
		$findingId = $this->request->getVar('findingId');

		$checkFinding = $findingModel->getById($findingId);
		if(empty($checkFinding)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data["findingData"] = $checkFinding;
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

	public function issue(){
		$trxModel = new TransactionModel();
		$findingModel = new FindingModel();

		$dateNow = new DateTime();

		$trxId = $this->request->getVar('trxId') ?? '';

		$checkTrx = $trxModel->where('trxId', $trxId)->get()->getRowArray();
		if(empty($checkTrx)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		try {
			$checkFinding = $findingModel->where("trxId", $trxId)->get()->getRowArray();
			$findingId = uuidv4();
			if(empty($checkFinding)){
				$dtInsertFinding = array(
					"findingId" => $findingId,
					"trxId" => $checkTrx["trxId"],
					"condition" => "Open",
					"openedAt" => $dateNow->format("Y-m-d H:i:s"),
					"openedBy" => $_SESSION["username"] ?? "user01",
					"findingPriority" => "Low"
				);

				$findingModel->insert($dtInsertFinding);
			} else {
				$findingId = $checkFinding["findingId"];
			}

			return redirect()->to("/Finding/detail?findingId=".$findingId);
		} catch (Exception $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
	}
}
