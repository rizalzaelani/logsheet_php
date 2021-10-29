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

class Asset extends BaseController
{
	use ResponseTrait;
	private $db;
	public function __construct()
	{
		$this->db = db_connect();
		helper('form');
	}
	public function index()
	{
		$assetModel			= new AssetModel();
		$tagModel			= new TagModel();
		$tagLocationModel	= new TagLocationModel();

		$asset			= $assetModel->findColumn('assetName') ?? [];
		$tag			= $tagModel->findColumn('tagName') ?? [];
		$tagLocation	= $tagLocationModel->findColumn('tagLocationName') ?? [];

		$data['asset']			= $asset;
		$data['tag']			= $tag;
		$data['tagLocation']	= $tagLocation;

		$data['title'] = 'Asset';
		$data['subtitle'] = 'Asset';
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
		// print("<pre />");
		// print_r($data);
		return $this->template->render('Master/Asset/index', $data);
	}

	public function datatable()
	{
		$table = 'vw_asset';
		$column_order = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
		$column_search = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
		$order = array('createdAt' => 'desc');
		$request = \Config\Services::request();
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);

		$filtTag = explode(",", $_POST["columns"][2]["search"]["value"] ?? '');
		$filtLoc = explode(",", $_POST["columns"][3]["search"]["value"] ?? '');
		$where = [
			'deletedAt' => null,
			// "(concat(',', tagName, ',') IN concat(',', " . $filtTag . ", ',') OR concat(',', tagLocationName, ',') IN concat(',', " . $filtLoc . ", ','))" => null
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

	public function add()
	{
		$modelAsset = new AssetModel();
		$modelTag = new TagModel();
		$modelLocation = new TagLocationModel();
		$modelStatus = new AssetStatusModel();

		$locationData = $this->db->table('tblm_tagLocation')->get()->getResult();
		$tagData = $this->db->table('tblm_tag')->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->get()->getResult();
		$data = array(
			'title' => "Add Asset",
			'subtitle' => "Add Asset",
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
				"title" => "Add Asset",
				"link" => "add"
			],
		];

		$data['asset'] = $modelAsset->findAll();
		$data['tagData'] = $tagData;
		$data['locationData'] = $locationData;
		$data['statusData'] = $statusData;
		return $this->template->render('Master/Asset/add', $data);
	}

	public function addAsset()
	{
		$assetModel = new AssetModel();
		$tagModel	= new TagModel();
		$tagLocationModel	= new TagLocationModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$assetTagModel = new AssetTagModel();
		$assetStatusModel = new AssetStatusModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();

		$post = $this->request->getPost();
		var_dump($post);
		die();
		$assetId = $post['assetId'];
		if (isset($post['assetId'])) {
			// asset
			$dataAsset = array(
				'assetId' => $assetId,
				'userId' => '',
				'assetStatusId' => $post['assetStatusId'],
				'assetName' => $post['assetName'],
				'assetNumber' => $post['assetNumber'],
				'description' => $post['assetDesc'],
				'schManual' => $post['schManual'],
				'schType' => $post['schType'],
				'schFrequency' => $post['schFrequency'] == '' ? 1 : (int)$post['schFrequency'],
				'schWeekDays' => $post['schWeekDays'],
				'schWeeks' => $post['schWeeks'],
				'schDays' => $post['schDays'],
				'latitude' => $post['latitude'],
				'longitude' => $post['longitude'],
			);
			$assetModel->insert($dataAsset);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $dataAsset));

			// tag and location new
			if ($post['tag'] != '') {
				$lengthAddTag = count($post['tag']);
				if ($lengthAddTag > 0) {
					for ($i = 0; $i < $lengthAddTag; $i++) {
						$dataAddTag = array(
							'tagId'			=> json_decode($post['tag'][$i])->addTagId,
							'tagName'		=> json_decode($post['tag'][$i])->addTagName,
							'description'	=> json_decode($post['tag'][$i])->addTagDesc,
						);
						$tagModel->insert($dataAddTag);
					}
				}
			}
			if ($post['location'] != '') {
				$lengthAddLocation = count($post['location']);
				if ($lengthAddLocation > 0) {
					for ($i = 0; $i < $lengthAddLocation; $i++) {
						$dataAddLocation = array(
							'tagLocationId'		=> json_decode($post['location'][$i])->addLocationId,
							'tagLocationName'	=> json_decode($post['location'][$i])->addLocationName,
							'latitude'			=> json_decode($post['location'][$i])->addLocationLatitude,
							'longitude'			=> json_decode($post['location'][$i])->addLocationLongitude,
							'description'		=> json_decode($post['location'][$i])->addLocationDesc,
						);
						$tagLocationModel->insert($dataAddLocation);
					}
				}
			}
			// taglocation
			$tagLocation = explode(",", $post['locationId']);
			$lengthTagLocation = count($tagLocation);
			$whereTagLocation = $tagLocation[0];
			if ($whereTagLocation != '') {
				for ($i = 0; $i < $lengthTagLocation; $i++) {
					$dataTagLocation = array(
						'assetTagLocationId' => null,
						'assetId' => $assetId,
						'tagLocationId' => $tagLocation[$i]
					);
					$assetTagLocationModel->insert($dataTagLocation);
					echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
				}
			} else {
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
			}

			// tag
			$tag = explode(",", $post['tagId']);
			$lengthTag = count($tag);
			$whereTag = $tag[0];
			if ($whereTag != '') {
				for ($i = 0; $i < $lengthTag; $i++) {
					$dataTag = array(
						'assetTagId' => null,
						'assetId' => $assetId,
						'tagId' => $tag[$i]
					);
					$assetTagModel->insert($dataTag);
					echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
				}
			} else {
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
			}

			// asset tagging
			$assetTaggingId = $post['assetTaggingId'];
			if ($post['assetTaggingtype'] != '') {
				$dataAssetTagging = array(
					'assetTaggingId' => $post['assetTaggingId'],
					'assetId' => $assetId,
					'assetTaggingValue' => $post['assetTaggingValue'],
					'assetTaggingtype' => $post['assetTaggingtype'],
					'description' => $post['assetTaggingDescription']
				);
				$assetTaggingModel->insert($dataAssetTagging);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully add data.', 'data' => $dataAssetTagging));
			} else {
				echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $post));
			}

			// asset parameter
			$lengthParam = count($post['parameter']);
			if ($post['parameter'][0] != '') {
				for ($i = 0; $i < $lengthParam; $i++) {
					$file = $this->request->getFile('photo' . json_decode($post['parameter'][$i])->parameterId);
					if ($file != '') {
						$name = "IMG_" . $file->getRandomName();
						$file->move('../public/assets/uploads/img', $name);
						$dataParam = array(
							'parameterId' => json_decode($post['parameter'][$i])->parameterId,
							'assetId' => $assetId,
							'sortId' => $i + 1,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo' => $name,
							'description' => json_decode($post['parameter'][$i])->paramDesc,
							'uom' => json_decode($post['parameter'][$i])->uom,
							'min' => (json_decode($post['parameter'][$i])->min) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->min,
							'max' => (json_decode($post['parameter'][$i])->max) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->max,
							'normal' => json_decode($post['parameter'][$i])->normal,
							'abnormal' => json_decode($post['parameter'][$i])->abnormal,
							'option' => json_decode($post['parameter'][$i])->option,
							'inputType' => json_decode($post['parameter'][$i])->inputType,
							'showOn' => json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
					} else {
						$dataParam = array(
							'parameterId' => json_decode($post['parameter'][$i])->parameterId,
							'assetId' => $assetId,
							'sortId' => $i + 1,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo' => '',
							'description' => json_decode($post['parameter'][$i])->paramDesc,
							'uom' => json_decode($post['parameter'][$i])->uom,
							'min' => (json_decode($post['parameter'][$i])->min) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->min,
							'max' => (json_decode($post['parameter'][$i])->max) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->max,
							'normal' => json_decode($post['parameter'][$i])->normal,
							'abnormal' => json_decode($post['parameter'][$i])->abnormal,
							'option' => json_decode($post['parameter'][$i])->option,
							'inputType' => json_decode($post['parameter'][$i])->inputType,
							'showOn' => json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
					}
				}
			}
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $post));
		}
		die();
	}

	public function detail($assetId)
	{
		$model = new AssetModel();
		$parameterModel = new ParameterModel();
		$assetStatusModel = new AssetStatusModel();
		$assetTaggingModel = new AssetTaggingModel();

		$assetData = $model->getById($assetId);
		$assetParameter = $this->db->table('tblm_parameter')->where('assetId', $assetId)->orderBy('sortId', 'asc')->getWhere('deletedAt', null)->getResultArray();

		// get value normal parameter
		$paramNormal = $this->db->table('tblm_parameter')->select('normal')->get()->getResultArray();
		$normalArray = [];
		foreach ($paramNormal as $key => $value) {
			array_push($normalArray, $value['normal']);
		}
		$normal = array_filter(array_unique(explode(",", implode(",", $normalArray))));

		// get value abnormal parameter
		$paramAbnormal = $this->db->table('tblm_parameter')->select('abnormal')->get()->getResultArray();
		$abnormalArray = [];
		foreach ($paramAbnormal as $key => $value) {
			array_push($abnormalArray, $value['abnormal']);
		}
		$abnormal = array_filter(array_unique(explode(",", implode(",", $abnormalArray))));

		$tagging = $assetTaggingModel->where('assetId', $assetId)->findAll();
		$tagData = $this->db->table('tblm_tag')->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->get()->getResult();
		$locationData = $this->db->table('tblm_tagLocation')->get()->getResult();

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
				"title"	=> "Detail",
				"link"	=> "detail"
			],
		];

		$data['parameter']		= $assetParameter;
		$data['normal']			= $normal;
		$data['abnormal']		= $abnormal;
		$data['assetData']		= $assetData;
		$data['tagData']		= $tagData;
		$data['locationData']	= $locationData;
		$data['statusData']		= $statusData;
		$data['tagging']		= $tagging;
		return $this->template->render('Master/Asset/detail2', $data);
	}

	public function saveSetting()
	{
		$assetModel = new AssetModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$assetTagModel = new AssetTagModel();
		$assetStatusModel = new AssetStatusModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();

		$post = $this->request->getPost();
		$assetId = $post['assetId'];

		// $test = $parameterModel->findColumn('normal');
		// $tblmParam = $this->db->table('tblm_parameter');
		// $tblmParam->select('normal');
		// $tblmParam->groupBy('normal');
		// $queryParam = $tblmParam->get()->getResult();
		// var_dump($queryParam);
		// var_dump(array_unique($test));

		if (isset($post['assetId'])) {

			// asset
			$dataAsset = array(
				'assetId' => $post['assetId'],
				'assetName' => $post['assetName'],
				'assetNumber' => $post['assetNumber'],
				'description' => $post['assetDesc'],
				'schManual' => $post['schManual'],
				'schType' => $post['schType'],
				'schFrequency' => $post['schFrequency'] == '' ? null : (int)$post['schFrequency'],
				'schWeekDays' => $post['schWeekDays'],
				'schWeeks' => $post['schWeeks'],
				'schDays' => $post['schDays'],
				'latitude' => $post['latitude'],
				'longitude' => $post['longitude'],
			);
			$assetModel->update($assetId, $dataAsset);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $dataAsset));

			// taglocation
			$tagLocation = explode(",", $post['locationId']);
			$lengthTagLocation = count($tagLocation);
			$whereTagLocation = $tagLocation[0];
			if ($whereTagLocation != '') {
				$assetTagLocationModel->deleteById($assetId);
				for ($i = 0; $i < $lengthTagLocation; $i++) {
					$dataTagLocation = array(
						'assetTagLocationId' => null,
						'assetId' => $assetId,
						'tagLocationId' => $tagLocation[$i]
					);
					$assetTagLocationModel->insert($dataTagLocation);
					echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
				}
			} else {
				$assetTagLocationModel->deleteById($assetId);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
			}

			// tag
			$tag = explode(",", $post['tagId']);
			$lengthTag = count($tag);
			$whereTag = $tag[0];
			if ($whereTag != '') {
				$assetTagModel->deleteById($assetId);
				for ($i = 0; $i < $lengthTag; $i++) {
					$dataTag = array(
						'assetTagId' => null,
						'assetId' => $assetId,
						'tagId' => $tag[$i]
					);
					$assetTagModel->insert($dataTag);
					echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
				}
			} else {
				$assetTagModel->deleteById($assetId);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
			}

			// asset status
			$assetStatusId = $post['assetStatusId'];
			$assetStatus = $assetStatusModel->where('assetStatusId', $assetStatusId)->get()->getResult();
			$lengthAssetStatus = count($assetStatus);
			if ($lengthAssetStatus < 1) {
				$dataStatus = array(
					'assetStatusId' => $post['assetStatusId'],
					'assetstatusName' => $post['assetStatusName']
				);
				$assetStatusModel->insert($dataStatus);
				$dataAssetStatus = array(
					'assetStatusId' => $post['assetStatusId']
				);
				$assetModel->update($assetId, $dataAssetStatus);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $dataStatus));
			} else {
				$dataAssetStatus = array(
					'assetStatusId' => $post['assetStatusId'],
				);
				$assetModel->update($assetId, $dataAssetStatus);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
			}

			// asset tagging
			$assetTagging = $assetTaggingModel->where('assetId', $assetId)->get()->getResult();
			$lengthTagging = count($assetTagging);
			$assetTaggingId = $post['assetTaggingId'];
			if ($post['assetTaggingValue'] != '') {
				if ($lengthTagging > 0) {
					$dataAssetTagging = array(
						'assetTaggingValue' => $post['assetTaggingValue'],
						'assetTaggingtype' => $post['assetTaggingType'],
						'description' => $post['assetTaggingDescription'],
					);
					$assetTaggingModel->update($assetTaggingId, $dataAssetTagging);
					echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $dataAssetTagging));
				} else {
					$dataAssetTagging = array(
						'assetId' => $assetId,
						'assetTaggingId' => $assetTaggingId,
						'assetTaggingValue' => $post['assetTaggingValue'],
						'assetTaggingtype' => $post['assetTaggingType'],
						'description' => $post['assetTaggingDescription']
					);
					$assetTaggingModel->insert($dataAssetTagging);
					echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $dataAssetTagging));
				}
			} else {
				$assetTaggingModel->deleteById($assetId);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $post));
			}

			// asset parameter
			$lengthParam = count($post['parameter']);
			if ($post['parameter'][0] != '') {
				for ($i = 0; $i < $lengthParam; $i++) {
					$file = $this->request->getFile('photo' . json_decode($post['parameter'][$i])->parameterId);
					if ($file != '') {
						$name = "IMG_" . $file->getRandomName();
						$file->move('../public/assets/uploads/img', $name);
						$dataParam = array(
							'parameterId' => json_decode($post['parameter'][$i])->parameterId,
							'assetId' => $assetId,
							'sortId' => (json_decode($post['parameter'][$i])->sortId) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->sortId,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo' => $name,
							'description' => json_decode($post['parameter'][$i])->description,
							'uom' => json_decode($post['parameter'][$i])->uom,
							'min' => (json_decode($post['parameter'][$i])->min) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->min,
							'max' => (json_decode($post['parameter'][$i])->max) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->max,
							'normal' => json_decode($post['parameter'][$i])->normal,
							'abnormal' => json_decode($post['parameter'][$i])->abnormal,
							'option' => json_decode($post['parameter'][$i])->option,
							'inputType' => json_decode($post['parameter'][$i])->inputType,
							'showOn' => json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
						// var_dump($dataParam);
					} else {
						$dataParam = array(
							'parameterId' => json_decode($post['parameter'][$i])->parameterId,
							'assetId' => $assetId,
							'sortId' => (json_decode($post['parameter'][$i])->sortId) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->sortId,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo' => '',
							'description' => json_decode($post['parameter'][$i])->description,
							'uom' => json_decode($post['parameter'][$i])->uom,
							'min' => (json_decode($post['parameter'][$i])->min) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->min,
							'max' => (json_decode($post['parameter'][$i])->max) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->max,
							'normal' => json_decode($post['parameter'][$i])->normal,
							'abnormal' => json_decode($post['parameter'][$i])->abnormal,
							'option' => json_decode($post['parameter'][$i])->option,
							'inputType' => json_decode($post['parameter'][$i])->inputType,
							'showOn' => json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
						// var_dump($dataParam);
					}
				}
			}
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $post));
		}
		die();
	}

	public function update()
	{
		$model = new AssetModel();
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		if (!empty($json->assetName) && !empty($json->assetNumber) && !empty($json->description)) {
			$data = array(
				'assetName' => $json->assetName,
				'assetNumber' => $json->assetNumber,
				'description' => $json->description
			);
			$model->update($assetId, $data);
			echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'All field cannot be empty!'));
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

	public function updateTag()
	{
		$assetTagModel = new AssetTagModel();
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		if ($json->assetId != '' && count($json->tagId) > 0) {
			$length = count($json->tagId);
			$assetTagModel->deleteById($assetId);
			for ($i = 0; $i < $length; $i++) {
				$data = array(
					'assetTagId' => null,
					'assetId' => $json->assetId,
					'tagId' => $json->tagId[$i]
				);
				$assetTagModel->insert($data);
			}
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		} else {
			$assetTagModel->deleteById($assetId);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		}
		die();
	}

	public function updateTagLocation()
	{
		$assetTagLocationModel = new AssetTagLocationModel();
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		if ($json->assetId != '' && count($json->tagLocationId) > 0) {
			$length = count($json->tagLocationId);
			$assetTagLocationModel->deleteById($assetId);
			for ($i = 0; $i < $length; $i++) {
				$data = array(
					'assetTagLocationId' => null,
					'assetId' => $json->assetId,
					'tagLocationId' => $json->tagLocationId[$i]
				);
				$assetTagLocationModel->insert($data);
			}
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		} else {
			$assetTagLocationModel->deleteById($assetId);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.'));
		}
		die();
	}

	public function updateOperation()
	{
		$assetModel = new AssetModel();
		$assetStatusModel = new AssetStatusModel();
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		$assetStatusId = $json->assetStatusId;
		$assetStatus = $assetStatusModel->where('assetStatusId', $assetStatusId)->get()->getResult();
		if (count($assetStatus) < 1) {
			$statusData = array(
				'assetStatusId' => $json->assetStatusId,
				'assetStatusName' => $json->assetStatusName,
			);
			$assetStatusModel->insert($statusData);
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

	public function updateTagging()
	{
		$assetTaggingModel = new AssetTaggingModel();
		$builder = $this->db->table('tblm_assetTagging');
		$json = $this->request->getJSON();
		$assetId = $json->assetId;
		$builder->where('assetId', $assetId);
		$result = $builder->get()->getResult();
		$assetTaggingId = $json->assetTaggingId;
		if ($json->assetId != '') {
			if (count($result) > 0) {
				$data = array(
					'assetId' => $assetId,
					'assetTaggingValue' => $json->assetTaggingValue,
					'assetTaggingtype' => $json->assetTaggingType,
					'description' => $json->description
				);
				$assetTaggingModel->update($assetTaggingId, $data);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
			} else {
				$dataTagging = array(
					'assetTaggingId' => null,
					'assetId' => $assetId,
					'assetTaggingValue' => $json->assetTaggingValue,
					'assetTaggingtype' => $json->assetTaggingType,
					'description' => $json->description
				);
				$assetTaggingModel->insert($dataTagging);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully add data.', 'data' => $dataTagging));
			}
		} else {
			echo json_encode(array('status' => 'success', 'message' => 'Bad Request!'));
		}
		die();
	}

	public function addTag()
	{
		$tagModel = new TagModel();
		$assetTagModel = new AssetTagModel();
		$json = $this->request->getJSON();
		if ($json->tagName != '') {
			$data = array(
				'tagId' => $json->tagId,
				'tagName' => $json->tagName,
				'description' => $json->description
			);
			$tagModel->insert($data);
			$dataAssetTag = array(
				'assetTagId' => null,
				'assetId' => $json->assetId,
				'tagId' => $json->tagId
			);
			$assetTagModel->insert($dataAssetTag);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully added data.', 'data' => $data));
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Field tag name cannot be empty!'));
		}
		die();
	}

	public function addTagLocation()
	{
		$tagLocationModel = new TagLocationModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$json = $this->request->getJSON();
		if ($json->tagLocationName != '') {
			$data = array(
				'tagLocationId' => $json->tagLocationId,
				'tagLocationName' => $json->tagLocationName,
				'latitude' => $json->latitude,
				'longitude' => $json->longitude,
				'description' => $json->description
			);
			$tagLocationModel->insert($data);
			$dataAssetTagLocation = array(
				'assetTagLocationId' => null,
				'assetId' => $json->assetId,
				'tagLocationId' => $json->tagLocationId
			);
			$assetTagLocationModel->insert($dataAssetTagLocation);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully added data.', 'data' => $data));
		} else {
			echo json_encode(array('status' => 'success', 'message' => 'Field location name cannot be empty!'));
		}
		die();
	}

	public function download()
	{
		return $this->response->download('../public/download/param1.xlsx', null);
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
		$parameterModel = new ParameterModel();
		$json = $this->request->getJSON();
		$dataParameter = $json->dataParam;
		$assetId = $json->assetId;
		$length = count($dataParameter);
		for ($i = 0; $i < $length; $i++) {
			$data = [
				'parameterId' => null,
				'assetId' => $assetId,
				'sortId' => null,
				'parameterName' => $dataParameter[$i]->parameterName,
				'description' => $dataParameter[$i]->description,
				(($dataParameter[$i]->inputType == "input") ? 'uom' : 'option') => $dataParameter[$i]->uom,
				(is_string($dataParameter[$i]->min) ? 'abnormal' : 'min') => $dataParameter[$i]->min,
				(is_string($dataParameter[$i]->max) ? 'normal' : 'max') => $dataParameter[$i]->max,
				'inputType' => $dataParameter[$i]->inputType,
				'showOn' => $dataParameter[$i]->showOn,
			];
			$parameterModel->insert($data);
		}
		echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		die();
	}

	public function addParameter()
	{
		$parameterModel = new ParameterModel();
		$request = \Config\Services::request();
		$post = $this->request->getPost();
		$file = $request->getFile('photo');
		if ($file != null) {
			$name = 'IMG_' . time() . '.png';
			$file->move('../uploads/img', $name);
			if ($post['assetId'] != '' && $post['parameterName'] != '') {
				$data = array(
					'parameterId' => $post['parameterId'],
					'assetId' => $post['assetId'],
					'sortId' => $post['sortId'],
					'parameterName' => $post['parameterName'],
					'photo' => $name,
					'description' => $post['description'],
					'uom' => $post['uom'],
					'min' => $post['min'],
					'max' => $post['max'],
					'normal' => $post['normal'],
					'abnormal' => $post['abnormal'],
					'option' => $post['option'],
					'inputType' => $post['inputType'],
					'showOn' => $post['showOn'],
				);
				$parameterModel->insert($data);
				echo json_encode(array('status' => 'success', 'message' => 'Success add parameter', 'data' => $data, 'file' => $file));
			} else {
				echo json_encode(array('status' => 'failed', 'message' => 'Field parameter name cannot be empty!'));
			}
		} else {
			if ($post['assetId'] != '' && $post['parameterName'] != '') {
				$data = array(
					'parameterId' => $post['parameterId'],
					'assetId' => $post['assetId'],
					'sortId' => $post['sortId'],
					'parameterName' => $post['parameterName'],
					'photo' => '',
					'description' => $post['description'],
					'uom' => $post['uom'],
					'min' => $post['min'],
					'max' => $post['max'],
					'normal' => $post['normal'],
					'abnormal' => $post['abnormal'],
					'option' => $post['option'],
					'inputType' => $post['inputType'],
					'showOn' => $post['showOn'],
				);
				$parameterModel->insert($data);
				echo json_encode(array('status' => 'success', 'message' => 'Success update parameter', 'data' => $data, 'file' => $file));
			} else {
				echo json_encode(array('status' => 'failed', 'message' => 'Field parameter name cannot be empty!'));
			}
		}
		die();
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
		$parameterModel = new ParameterModel();
		$builder = $this->db->table('tblm_parameter');
		$request = \Config\Services::request();
		$post = $this->request->getPost();
		$parameterId = $post['parameterId'];
		$builder->select('photo')->where('parameterId', $parameterId);
		$photo = $builder->get()->getResult();
		$file = $request->getFile('photo');
		$isNull = ($file == NULL) ? 'true' : 'false';
		if ($isNull == 'false') {
			if ($photo[0]->photo != '') {
				unlink('../public/assets/uploads/img/' . $photo[0]->photo);
			}
			$name = "IMG_" . $file->getRandomName();
			$file->move('../public/assets/uploads/img', $name);
			if ($post['assetId'] != '' && $post['parameterName'] != '') {
				$data = array(
					'assetId' => $post['assetId'],
					'sortId' => $post['sortId'] == "null" || "" || 0 ? null : $post['sortId'],
					'parameterName' => $post['parameterName'],
					'photo' => $name,
					'description' => $post['description'],
					'uom' => $post['uom'],
					'min' => $post['min'] == "null" || "" || 0 ? null : $post['min'],
					'max' => $post['max'] == "null" || "" || 0 ? null : $post['max'],
					'normal' => $post['normal'],
					'abnormal' => $post['abnormal'],
					'option' => $post['option'],
					'inputType' => $post['inputType'],
					'showOn' => $post['showOn'],
				);
				$parameterModel->update($parameterId, $data);
				echo json_encode(array('status' => 'success', 'message' => 'Success update parameter', 'data' => $data, 'file' => $file));
				die();
			} else {
				echo json_encode(array('status' => 'failed', 'message' => 'Field parameter name cannot be empty!'));
			}
		} else if ($isNull == 'true') {
			if ($post['assetId'] != '' && $post['parameterName'] != '') {
				$data = array(
					'assetId' => $post['assetId'],
					'sortId' => $post['sortId'] == "null" || "" || 0 ? null : $post['sortId'],
					'parameterName' => $post['parameterName'],
					'photo' => $post['photo'],
					'description' => $post['description'],
					'uom' => $post['uom'],
					'min' => $post['min'] == "null" || "" || 0 ? null : $post['min'],
					'max' => $post['max'] == "null" || "" || 0 ? null : $post['max'],
					'normal' => $post['normal'],
					'abnormal' => $post['abnormal'],
					'option' => $post['option'],
					'inputType' => $post['inputType'],
					'showOn' => $post['showOn'],
				);
				$parameterModel->update($parameterId, $data);
				echo json_encode(array('status' => 'success', 'message' => 'Success update parameter test', 'data' => $data));
				die();
			} else {
				echo json_encode(array('status' => 'failed', 'message' => 'Field parameter name cannot be empty!'));
			}
		}
		die();
	}

	public function updateParameter2()
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
		if ($json->parameterId) {
			$model->delete($id);
			echo json_encode(array('status' => 'success', 'data' => $id));
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad request!', 'data' => $json));
		}
		die();
	}

	public function sortingParameter()
	{
		$parameterModel = new ParameterModel();
		$json = $this->request->getJSON();
		$data = $json->data;
		$length = count(($data));
		// var_dump($data);
		foreach ($data as $key => $value) {
			$dataSort = array(
				'sortId' => $value[0]
			);
			$parameterModel->update($value[1], $dataSort);
		}
		echo json_encode(array('status' => 'success', 'message' => '', 'data' => $json));
		die();
	}
}
