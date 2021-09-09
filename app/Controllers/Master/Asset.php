<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\TagModel;
use CodeIgniter\API\ResponseTrait;

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
		// $model = new AssetModel();
		// $data = $model->findAll();
		// return $data;
		$data = array(
			'title' => 'Asset',
			'subtitle' => 'Asset',
		);

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Asset",
				"link"	=> "Asset"
			],
		];

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
		// return json_encode($dummy);
		$data = array(
			'title' => 'Detail Asset',
			'subtitle' => 'Detail',
		);

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Asset",
				"link"	=> "Asset"
			],
			[
				"title" => "Detail",
				"link" => "detail"
			],
		];
		return $this->template->render('Master/Asset/detail', $data);
	}

	public function getParam()
	{
		$dummy = array(
			"parameterName" => "PING",
			"photo" => "photo.jpg",
			"description" => "desc Parameter",
			"uom" => "ms",
			"min" => 35,
			"max" => 65,
			"showOn" => "Running, Standby",
		);
		echo json_encode($dummy);
	}

	public function add()
	{
		$modelTag = new TagModel();
		$data = array(
			'title' => "Add Asset",
			'data' => $modelTag->findAll()
		);
		return $this->template->render('Master/Asset/add', $data);
	}

	public function dataTag()
	{
		$modelTag = new TagModel();
		$data = array(
			'data' => $modelTag->findAll()
		);
		return json_encode($data);
	}
}
