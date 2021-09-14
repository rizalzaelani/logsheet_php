<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\TagModel;
use App\Models\LocationModel;
use App\Models\StatusAssetModel;
use App\Models\AssetTagModel;
use App\Models\ParameterModel;
use CodeIgniter\API\ResponseTrait;

use Dompdf\Dompdf;

class Asset extends BaseController
{
	use ResponseTrait;
	private $db;
	public function __construct()
	{
		$this->db = db_connect();
		$model = new AssetModel();
		helper('form');
	}
	public function index()
	{
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

	public function detail($assetId)
	{
		$assetModel = new AssetModel();
		$parameter = new ParameterModel();
		$statusModel = new StatusAssetModel();
		$tagModel = new TagModel();
		$assetTagModel = new AssetTagModel();
		$asset = $assetModel->where('assetId', $assetId)->first();
		$assetParameter = $parameter->where('assetId', $assetId)->findAll();
		$assetStatus = $statusModel->where('assetStatusId', $asset['assetStatusId'])->first();
		$assetTag = $assetTagModel->where('assetId', $assetId)->findAll();
		$builder = $this->db->table('tblm_asset as a');
		$builder->select('a.assetId as assetId, a.userId as userId, a.assetName as assetName, a.assetNumber as assetNumber, a.description, a.frequencyType, a.frequency, a.createdAt, a.updatedAt, a.deletedAt');
		$builder->select('CASE WHEN group_concat(c.tagName separator ",") IS NULL THEN "-" ELSE group_concat(c.tagName separator ",") END AS tagName');
		$builder->select('CASE WHEN (c.tagId) IS NULL THEN "-" ELSE (c.tagId) END AS tagId');
		$builder->select('CASE WHEN group_concat(e.tagLocationName separator ",") IS NULL THEN "-" ELSE (e.tagLocationName) END AS tagLocationName');
		$builder->select('f.assetStatusId as assetStatus Id, f.assetStatusName as assetStatusName');
		$builder->join('tblm_assetStatus as f', 'f.assetStatusId = a.assetStatusId');
		$builder->join('tblmb_assetTag as b', 'b.assetId = a.assetId', 'LEFT');
		$builder->join('tblm_tag as c', 'c.tagId = b.tagId', 'LEFT');
		$builder->join('tblmb_assetTagLocation as d', 'd.assetId = a.assetId', 'LEFT');
		$builder->join('tblm_tagLocation as e', 'e.tagLocationId = d.tagLocationId', 'LEFT');
		$builder->groupBy('a.assetId, a.userId, a.assetName, a.assetNumber, a.description, a.frequencyType, a.frequency, a.createdAt, a.updatedAt, a.deletedAt');
		$query = $builder->where('a.assetId', $assetId)->get()->getResult();
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
		$data['asset'] = $asset;
		$data['parameter'] = $assetParameter;
		$data['data'] = json_decode(json_encode($query), TRUE);
		$data['assetStatus'] = $assetStatus;
		$data['status'] = $statusModel->findAll();
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
		$modelAsset = new AssetModel();
		$modelTag = new TagModel();
		$modelLocation = new LocationModel();
		$modelStatus = new StatusAssetModel();
		$data = array(
			'title' => "Add Asset",
		);
		$data['asset'] = $modelAsset->findAll();
		$data['tag'] = $modelTag->findAll();
		$data['location'] = $modelLocation->findAll();
		$data['status'] = $modelStatus->findAll();
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
				"title" => "Add Asset",
				"link" => "add"
			],
		];
		return $this->template->render('Master/Asset/add', $data);
	}

	public function save()
	{
		$data = $this->request->getJSON();
		if (!empty($this->request->getJSON())) {
			$json  = $this->request->getJSON();
			$data = array(
				'assetId' => $json->assetId,
				'assetName' => $json->assetName,
				'description' => $json->description,
				'latitude' => $json->latitude,
				'longitude' => $json->longitude,
				'frequencyType' => $json->frequencyType,
				'tag' => $json->tag,
				'location' => $json->location,
			);

			echo json_encode(array('status' => 'success', 'data' => $data));
			die();
		}
	}

	public function addParameter()
	{
		$model = new ParameterModel();
		$json = $this->request->getJSON();
		if (isset($json)) {
			$data = array(
				'parameterId' => $json->parameterId,
				'assetId' => $json->assetId,
				'sortId' => $json->sortId,
				'parameterName' => $json->parameterName,
				'photo' => $json->photo,
				'description' => $json->description,
				'uom' => $json->uom,
				'min' => $json->min,
				'max' => $json->max,
				'normal' => $json->normal,
				'abnormal' => $json->abnormal,
				'option' => $json->option,
				'inputType' => $json->inputType,
				'showOn' => $json->showOn,
			);
			$model->insert($data);
			echo json_encode(array('status' => 'success', 'message' => 'Success add parameter', 'data' => $data));
			die();
		}
	}

	public function editParameter()
	{
		$model = new ParameterModel();
		$json = $this->request->getJSON();
		$parameter = $model->where('parameterId', $json->parameterId)->findAll();
		$data['parameter'] = $parameter;
		echo json_encode(array('status' => 'success', 'data' => $parameter));
		die();
	}

	public function updateParameter()
	{
		$model = new ParameterModel();
		$json = $this->request->getJSON();
		$id = $json->parameterId;
		$data = array(
			'assetId' => $json->assetId,
			'sortId' => $json->sortId,
			'parameterName' => $json->parameterName,
			'photo' => $json->photo,
			'description' => $json->description,
			'uom' => $json->uom,
			'min' => $json->min,
			'max' => $json->max,
			'normal' => $json->normal,
			'abnormal' => $json->abnormal,
			'option' => $json->option,
			'inputType' => $json->inputType,
			'showOn' => $json->showOn,
		);
		$model->update($id, $data);
		echo json_encode(array('status' => 'success', 'data' => $data));
		die();
	}

	public function deleteParameter()
	{
		$model = new ParameterModel();
		$json = $this->request->getJSON();
		$id = $json->parameterId;
		$model->delete($id);
		echo json_encode(array('status' => 'success', 'data' => $id));
		die();
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
