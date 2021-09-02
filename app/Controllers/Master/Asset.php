<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Controllers\RESTful\ResourceController;
use App\Models\AssetModel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\XLSX\Reader;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Row;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpParser\Node\Stmt\Echo_;

use Dompdf\Dompdf;

class Asset extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$model = new AssetModel();
		helper('form');
	}
	public function index()
	{
		$model = new AssetModel();
		$company = array('IPC');
		$area = array('GEDUNG PARKIR', 'GEDUNG KAS', 'GEDUNG MAINTENANCE', 'GEDUNG FINANCE', "GEDUNG COB", "GEDUNG MESIN");
		$unit = array('CCTV', 'ROUTER', 'IT');
		$data = array(
			'title' => 'Asset',
			'subtitle' => 'Asset',
			'getCompany' => $company,
			'getArea' => $area,
			'getUnit' => $unit,
		);
		return $this->template->render('Master/Asset/index', $data);
	}
	public function datatable()
	{
		$model = new AssetModel();
		// $area = array('GEDUNG PARKIR', 'GEDUNG KAS', 'GEDUNG MAINTENANCE', 'GEDUNG FINANCE', "GEDUNG COB");
		$table = 'tblm_asset';
		$column_order = array('assetId', 'assetName', 'assetNumber', 'description');
		$column_search = array('assetId', 'assetName', 'assetNumber', 'description');
		$order = array('createdAt' => 'asc');
		$request = \Config\Services::request();
		$datatable = new \App\Models\_datatable($table, $column_order, $column_search, $order);
		$where = [];
		$list = $datatable->datatable($where);
		$output = array(
			"draw" => $request->getPost('draw'),
			"recordsTotal" => $datatable->count_all($where),
			"recordsFiltered" => $datatable->count_filtered($where),
			"data" => $list,
			// "getData" => $area
			'status' => 200,
			'message' => 'success'
		);
		echo json_encode($output);
	}

	public function detail()
	{
		$data = array(
			'title' => 'Detail',
			'subtitle' => 'Detail',
		);
		return $this->template->render('Master/Asset/detail', $data);
	}

	public function add()
	{
		$data = array(
			'title' => "Add Asset"
		);
		return $this->template->render('Master/Asset/add', $data);
	}
}
