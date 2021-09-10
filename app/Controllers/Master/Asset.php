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
	private $db;
	function __construct()
	{
		$this->db = db_connect();
		$model = new AssetModel();
		helper('form');
	}
	public function index()
	{
		// $query = $this->db->query('SELECT t.tagName, t.description, s.assetName, s.assetNumber FROM tblm_tag t RIGHT JOIN tblmb_assetTag b ON b.tagId = t.tagId JOIN tblm_asset s ON b.assetId = s.assetId');
		// $results = $query->getResult();
		// $builder = $this->db->table('tblm_asset as asset');
		// $builder->select('* , GROUP_CONCAT(tag.tagName) as tag_name');
		// $builder->join('tblmb_assetTag as assetTag', 'assetTag.assetId = asset.assetId');
		// $builder->join('tblm_tag as tag', 'tag.tagId = assetTag.tagId');
		// $builder->groupBy('asset.assetId');
		// $data = $builder->get()->getResult();
		// $json = json_encode($data);
		// d($json);
		// foreach ($data as $key) {
		// 	d($key->tag_name);
		// }
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
		$table = 'vw_asset';
		$column_order = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'frequencyType', 'createdAt');
		$column_search = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'frequencyType', 'createdAt');
		$order = array('createdAt' => 'asc');
		$request = \Config\Services::request();
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
		$where = [];
		$list = $DTModel->datatable($where);
		$output = array(
			"draw" => $request->getPost('draw'),
			"recordsTotal" => $DTModel->count_all($where),
			"recordsFiltered" => $DTModel->count_filtered($where),
			"data" => $list,
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
