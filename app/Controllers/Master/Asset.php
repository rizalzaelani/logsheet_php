<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AssetTaggingModel;
use App\Models\AssetTagLocationModel;
use App\Models\TagModel;
use App\Models\TagLocationModel;
use App\Models\AssetStatusModel;
use App\Models\AssetTagModel;
use App\Models\ParameterModel;
use CodeIgniter\API\ResponseTrait;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\XLSX\Reader;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Row;

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
		$where = [
			'deletedAt' => null
		];
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

	public function detail2($assetId)
	{
		$model = new AssetModel();
		$parameterModel = new ParameterModel();
		$assetStatusModel = new AssetStatusModel();
		$assetTaggingModel = new AssetTaggingModel();

		$assetData = $model->getById($assetId);

		$assetParameter = $parameterModel->where('assetId', $assetId)->findAll();
		$tagging = $assetTaggingModel->where('assetId', $assetId)->findAll();
		$tagData = $this->db->table('tblm_tag')->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->get()->getResult();
		$locationData = $this->db->table('tblm_tagLocation')->get()->getResult();

		$data = array(
			'title' => 'Detail Asset',
			'subtitle' => 'Detail',
		);

		$data['parameter'] = $assetParameter;
		$data['assetData'] = $assetData;
		$data['tagData'] = $tagData;
		$data['locationData'] = $locationData;
		$data['statusData'] = $statusData;
		$data['tagging'] = $tagging;
		return $this->template->render('Master/Asset/detail', $data);
	}

	public function detail($assetId)
	{
		$assetModel = new AssetModel();
		$parameter = new ParameterModel();
		$statusModel = new AssetStatusModel();
		$tagModel = new TagModel();
		$tagLocation = new TagLocationModel();
		$assetTagModel = new AssetTagModel();
		$assetTagging = new  AssetTaggingModel();

		$asset = $assetModel->where('assetId', $assetId)->first();
		$assetParameter = $parameter->where('assetId', $assetId)->findAll();
		$tagging = $assetTagging->where('assetId', $assetId)->findAll();
		$assetStatus = $statusModel->where('assetStatusId', $asset['assetStatusId'])->first();
		$assetTag = $assetTagModel->where('assetId', $assetId)->findAll();

		$assetTag2 = $this->db->table('tblm_tag');
		$assetTag2->select('tagId, tagName');
		$queryTag = $assetTag2->get()->getResult();
		// d($queryTag);
		$tag = $tagModel->findColumn('tagName');
		$tagId = $tagModel->findColumn('tagId');
		$location = $tagLocation->findColumn('tagLocationName');
		$locationId = $tagLocation->findColumn('tagLocationId');

		$builder = $this->db->table('tblm_asset as a');
		$builder->select('a.assetId as assetId, a.userId as userId, a.assetName as assetName, a.assetNumber as assetNumber, a.description, a.frequencyType, a.frequency, a.createdAt, a.updatedAt, a.deletedAt');
		$builder->select('CASE WHEN group_concat(distinct c.tagName separator ";") IS NULL THEN "-" ELSE group_concat(distinct c.tagName separator ";") END AS tagName');
		$builder->select('CASE WHEN group_concat(distinct c.tagId separator ";") IS NULL THEN "-" ELSE group_concat(distinct c.tagId separator ";") END AS tagId');
		$builder->select('CASE WHEN group_concat(distinct e.tagLocationName separator ";") IS NULL THEN "-" ELSE group_concat(distinct e.tagLocationName separator ";") END AS tagLocationName');
		$builder->select('CASE WHEN group_concat(distinct e.tagLocationId separator ";") IS NULL THEN "-" ELSE group_concat(distinct e.tagLocationId separator ";") END AS tagLocationId');
		$builder->select('f.assetStatusId as assetStatus Id, f.assetStatusName as assetStatusName');
		$builder->join('tblm_assetStatus as f', 'f.assetStatusId = a.assetStatusId');
		$builder->join('tblmb_assetTag as b', 'b.assetId = a.assetId', 'LEFT');
		$builder->join('tblm_tag as c', 'c.tagId = b.tagId', 'LEFT');
		$builder->join('tblmb_assetTagLocation as d', 'd.assetId = a.assetId', 'LEFT');
		$builder->join('tblm_tagLocation as e', 'e.tagLocationId = d.tagLocationId', 'LEFT');
		$builder->groupBy('a.assetId, a.userId, a.assetName, a.assetNumber, a.description, a.frequencyType, a.frequency, a.createdAt, a.updatedAt, a.deletedAt');
		$query = $builder->where('a.assetId', $assetId)->get()->getResult();
		$tagSelected = (array_values(array_unique(explode(";", $query[0]->tagName))));
		$tagIdSelected = (array_values(array_unique(explode(";", $query[0]->tagId))));
		$locationSelected = (array_values(array_unique(explode(";", $query[0]->tagLocationName))));
		$locationIdSelected = (array_values(array_unique(explode(";", $query[0]->tagLocationId))));
		$data = array(
			'title' => 'Detail Asset',
			'subtitle' => 'Detail',
		);

		// select option tag
		$pushTagSelected = array($tagIdSelected, $tagSelected);
		$pushTag = [];
		$pushTagId = array_push($pushTag, $tagId);
		$pushTagName = array_push($pushTag, $tag);
		$tagIdSelected2 = (array_intersect($pushTagSelected[0], $pushTag[0]));
		$tagNameSelected = (array_intersect($pushTagSelected[1], $pushTag[1]));
		$tagg = [];
		$t = array_push($tagg, $tagIdSelected2);
		$t = array_push($tagg, $tagNameSelected);
		// d($pushTag);

		$pushLocationSelected = array($locationIdSelected, $locationSelected);
		$pushLocation = [];
		$pushLocationId = array_push($pushLocation, $locationId);
		$pushLocationName = array_push($pushLocation, $location);
		$locationIdSelected2 = (array_intersect($pushLocationSelected[0], $pushLocation[0]));
		$locationNameSelected = (array_intersect($pushLocationSelected[1], $pushLocation[1]));
		$loc = [];
		$t = array_push($loc, $locationIdSelected2);
		$t = array_push($loc, $locationNameSelected);
		// d($loc);

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
		$data['selectedTag'] = $tagg;
		$data['selectedLocation'] = $loc;
		$data['queryTag'] = $queryTag;
		$data['assetTag'] = array_intersect($tag, $tagSelected);
		$data['tagUnselect'] = array_diff($tag, $tagSelected);
		$data['assetTagId'] = array_intersect($tagId, $tagIdSelected);
		$data['assetTagIdUnselect'] = array_diff($tagId, $tagIdSelected);
		$data['assetLocation'] = array_intersect($location, $locationSelected);
		$data['locationUnselect'] = array_diff($location, $locationSelected);
		$data['assetLocationId'] = array_intersect($locationId, $locationIdSelected);
		$data['assetLocationIdUnSelect'] = array_diff($locationId, $locationIdSelected);
		$data['assetStatus'] = $assetStatus;
		$data['tagging'] = $tagging;
		$data['status'] = $statusModel->findAll();
		// d(array_values(array_unique(explode(";", $query[0]->tagName))));
		// echo json_encode(array('tag' => $query));
		// d($queryTag);
		// d(explode(";", $query[0]->tagName), explode(";", $query[0]->tagId));
		return $this->template->render('Master/Asset/detail', $data);
	}

	public function update()
	{
		$model = new AssetModel();
		$json = $this->request->getJSON();
		$id = $json->assetId;
		if (!empty($json->assetName)) {
			$data = array(
				'assetName' => $json->assetName,
				'assetNumber' => $json->assetNumber,
				'description' => $json->description
			);
			$model->update($id, $data);
			echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Field asset name cannot be empty!'));
		}
		die();
	}

	public function delete()
	{
		$model = new AssetModel();
		$json  = $this->request->getJSON();
		if (!empty($json->assetId)) {
			$model->delete($json->assetId);
			echo json_encode(array('status' => 'success', 'message' => 'success delete data', 'data' => $json));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Bad Request!', 'data' => $json));
		}
		die();
	}

	public function addTag()
	{
		$model = new AssetTagModel();
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		if ($json->assetId != '' && count($json->tagId) > 0) {
			$length = count($json->tagId);
			$model->deleteById($assetId);
			for ($i = 0; $i < $length; $i++) {
				$data = array(
					'assetTagId' => null,
					'assetId' => $json->assetId,
					'tagId' => $json->tagId[$i]
				);
				$model->insert($data);
			}
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		} else {
			$model->deleteById($assetId);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		}
		die();
	}

	public function addTagLocation()
	{
		$model = new AssetTagLocationModel();
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		if ($json->assetId != '' && count($json->tagLocationId) > 0) {
			$length = count($json->tagLocationId);
			$model->deleteById($assetId);
			for ($i = 0; $i < $length; $i++) {
				$data = array(
					'assetTagLocationId' => null,
					'assetId' => $json->assetId,
					'tagLocationId' => $json->tagLocationId[$i]
				);
				$model->insert($data);
			}
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		} else {
			$model->deleteById($assetId);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		}
		die();
	}

	public function updateOperation()
	{
		$assetModel = new AssetModel();
		$statusModel = new AssetStatusModel();
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		$statusId = $json->assetStatusId;
		$assetStatus = $statusModel->where('assetStatusId', $statusId)->get()->getResult();
		if (count($assetStatus) < 1) {
			$statusData = array(
				'assetStatusId' => $json->assetStatusId,
				'assetStatusName' => $json->assetStatusName,
			);
			$statusModel->insert($statusData);
			$data = array(
				'assetStatusId' => $json->assetStatusId
			);
			$assetModel->update($assetId, $data);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		} else {
			$data = array(
				'assetStatusId' => $json->assetStatusId,
			);
			$assetModel->update($assetId, $data);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		}
		die();
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
		$modelLocation = new TagLocationModel();
		$modelStatus = new AssetStatusModel();
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

	public function uploadFile()
	{
		$file = $this->request->getFile('importParam');
		if ($file) {
			$newName = "doc" . time();
			$file->move('../uploads', $newName);
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('../uploads/' . $newName);
			$dataImport = [];
			foreach ($reader->getSheetIterator() as $sheet) {
				$numrow = 1;
				foreach ($sheet->getRowIterator() as $row) {
					if ($numrow > 1) {
						if ($row->getCellAtIndex(1) != '' && $row->getCellAtIndex(2) != '') {
							$dataImport[] = array(
								'parameterName' => $row->getCellAtIndex(1)->getValue(),
								'description' => $row->getCellAtIndex(2)->getValue(),
								'uom' => (($row->getCellAtIndex(8)->getValue()) ? $row->getCellAtIndex(8)->getValue() : $row->getCellAtIndex(3)->getValue()),
								'min' => (($row->getCellAtIndex(7)->getValue()) ? $row->getCellAtIndex(7)->getValue() : $row->getCellAtIndex(4)->getValue()),
								'max' => (($row->getCellAtIndex(6)->getValue()) ? $row->getCellAtIndex(6)->getValue() : $row->getCellAtIndex(5)->getValue()),
								'inputType' => $row->getCellAtIndex(9)->getValue(),
								'showOn' => $row->getCellAtIndex(10)->getValue(),
							);
						} else {
							return $this->response->setJSON(array('status' => 'failed', 'message' => 'Data Does Not Match'));
						}
					}
					$numrow++;
				}
			}
			unlink('../uploads/' . $newName);
			if ($dataImport) {
				return $this->response->setJSON(array('status' => 'success', 'message' => '', 'data' => $dataImport));
			} else {
				return $this->response->setJSON(array('status' => 'failed', 'message' => 'Data Not Found!'));
			}
		} else {
			return $this->response->setJSON((array('status' => 'failed', 'message' => 'Bad Request!')));
		}
	}

	public function insertParameter()
	{
		$model = new ParameterModel();
		$json = $this->request->getJSON();
		$dataParam = $json->dataParam;
		$assetId = $json->assetId;
		$length = count($dataParam);
		for ($i = 0; $i < $length; $i++) {
			$data = [
				'parameterId' => null,
				'assetId' => $assetId,
				'sortId' => null,
				'parameterName' => $dataParam[$i]->parameterName,
				'description' => $dataParam[$i]->description,
				(($dataParam[$i]->inputType == "input") ? 'uom' : 'option') => $dataParam[$i]->uom,
				(is_string($dataParam[$i]->min) ? 'abnormal' : 'min') => $dataParam[$i]->min,
				(is_string($dataParam[$i]->max) ? 'normal' : 'max') => $dataParam[$i]->max,
				'inputType' => $dataParam[$i]->inputType,
				'showOn' => $dataParam[$i]->showOn,
			];
			$model->insert($data);
		}
		echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		die();
	}

	public function addParameter()
	{
		$model = new ParameterModel();
		$file = $this->request->getFile('photo');
		$json = $this->request->getJSON();
		if (isset($json)) {
			$data = array(
				'test' => $file,
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
			// $model->insert($data);
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
