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
use App\Models\Influx\LogActivityModel;
use App\Models\Influx\LogModel;
use App\Models\ParameterModel;
use App\Models\Wizard\TransactionModel;
use CodeIgniter\API\ResponseTrait;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use DateTime;
use Exception;

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
		if (!checkRoleList("MASTER.ASSET.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}
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
		return $this->template->render('Master/Asset/index', $data);
	}

	public function datatable()
	{
		$request = \Config\Services::request();

		if (!checkRoleList("MASTER.ASSET.VIEW")) {
			echo json_encode(array(
				"draw" => $request->getPost('draw'),
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => [],
				'status' => 403,
				'message' => "You don't have access to this page"
			));
		}

		$table = 'vw_asset';
		$column_order = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
		$column_search = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
		$order = array('createdAt' => 'desc');
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);

		$where = [
			'userId' => $this->session->get("adminId"),
			'deletedAt' => null
		];

		$filtTag = $_POST["columns"][1]["search"]["value"] ?? '';
		$filtLoc = $_POST["columns"][2]["search"]["value"] ?? '';

		if ($filtTag != '') $where["find_in_set_multiple('$filtTag', tagId)"] = null;
		if ($filtLoc != '') $where["find_in_set_multiple('$filtLoc', tagLocationId)"] = null;

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

	public function changelog()
	{
		$influxModel = new LogModel();

		$post = $this->request->getPost();
		$datestart = $post['start'];
		$dateend = $post['end'];
		$assetId = $post['assetId'];
		$activity = "Update asset";
		$adminId = $this->session->get('adminId');
		$activity = 'Update asset';

		try {
			$data = $influxModel->getLogAsset($activity, $adminId, $assetId, $datestart, $dateend);
			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success get data',
				'data' => $data
			));
		} catch (Exception $e) {
			return $this->response->setJSON(array(
				'status' => 500,
				'message' => $e->getMessage(),
				'data' => ''
			));
		}
	}

	public function add()
	{
		if (!checkRoleList("MASTER.ASSET.ADD")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$modelAsset = new AssetModel();
		$adminId = $this->session->get('adminId');

		$locationData = $this->db->table('tblm_tagLocation')->where(['userId' => $adminId])->get()->getResult();
		$tagData = $this->db->table('tblm_tag')->where(['userId' => $adminId])->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->where(['deletedAt' => null, 'userId' => $adminId])->get()->getResult();
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
		if (!checkRoleList("MASTER.ASSET.ADD")) {
			return $this->response->setJSON([
				'status' => 403,
				'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
		}

		$assetModel = new AssetModel();
		$tagModel	= new TagModel();
		$tagLocationModel	= new TagLocationModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$assetTagModel = new AssetTagModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();

		$post = $this->request->getPost();
		$assetId = $post['assetId'];

		$dirPath = 'upload/Asset';
		$fileAsset = $this->request->getFile('photo');
		$namePhoto = "";
		if ($fileAsset != null) {
			if (!is_dir($dirPath)) {
				mkdir($dirPath);
			}
			$dirPhoto = $dirPath . '/' . 'file' . $this->session->get('adminId');
			if (!is_dir($dirPhoto)) {
				mkdir($dirPhoto);
			}
			$namePhoto = 'assetPhoto' . $fileAsset->getRandomName();
			$image1 = \Config\Services::image()
				->withFile($fileAsset)
				->save($dirPhoto . '/' . $namePhoto);
		}

		try {
			$dirPath = 'upload/Asset';
			$fileAsset = $this->request->getFile('photo');
			$namePhoto = "";
			$dirPhoto = "";
			if ($fileAsset != null) {
				if (!is_dir($dirPath)) {
					mkdir($dirPath);
				}
				$dirPhoto = $dirPath . '/' . 'file' . $this->session->get('adminId');
				if (!is_dir($dirPhoto)) {
					mkdir($dirPhoto);
				}
				$namePhoto = 'IMG_' . $fileAsset->getRandomName();
				$image1 = \Config\Services::image()
					->withFile($fileAsset)
					->resize(480, 480, true, 'heigth')
					->save($dirPhoto . '/' . $namePhoto);
			}
			// asset
			$dataAsset = array(
				'assetId'		=> $assetId,
				'userId'		=> $this->session->get("adminId"),
				'assetStatusId' => $post['assetStatusId'],
				'assetName'		=> $post['assetName'],
				'assetNumber'	=> $post['assetNumber'],
				'photo'			=> $namePhoto == "" ? null : (base_url() . '/' . $dirPhoto . '/' . $namePhoto),
				'description'	=> $post['assetDesc'],
				'schManual'		=> $post['schManual'],
				'schType'		=> $post['schType'],
				'schFrequency'	=> $post['schFrequency'] == '' ? 1 : (int)$post['schFrequency'],
				'schWeekDays'	=> $post['schWeekDays'],
				'schWeeks'		=> $post['schWeeks'],
				'schDays'		=> $post['schDays'],
				'latitude'		=> $post['latitude'],
				'longitude'		=> $post['longitude'],
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
							'userId'		=> $this->session->get("adminId"),
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
							'userId'			=> $this->session->get("adminId"),
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
			$lengthTagging = count($post['assetTagging']);
			if ($lengthTagging) {
				$getTagging = $post['assetTagging'];
				for ($i = 0; $i < $lengthTagging; $i++) {
					$dataTagging = json_decode($getTagging[$i]);
					if ($dataTagging->assetTaggingValue != '') {
						$assetTaggingModel->insert($dataTagging);
					}
				}
			}

			// asset parameter
			$lengthParam = count($post['parameter']);
			$dirPath = 'upload/Asset';
			if ($post['parameter'][0] != '') {
				for ($i = 0; $i < $lengthParam; $i++) {
					$file = $this->request->getFile('photo' . json_decode($post['parameter'][$i])->parameterId);
					if ($file != '') {
						$name1 = "";
						$name2 = "";
						$name3 = "";
						if (!is_dir($dirPath)) {
							mkdir($dirPath);
						}
						$dirPhoto = $dirPath . '/' . 'file' . $this->session->get('adminId');
						if (!is_dir($dirPhoto)) {
							mkdir($dirPhoto);
						}
						$name1 = 'IMG_PARAM_H_' . $file->getRandomName();
						$image1 = \Config\Services::image()
							->withFile($file)
							->save($dirPhoto . '/' . $name1);
						$name2 = 'IMG_PARAM_M_' . $file->getRandomName();
						$image2 = \Config\Services::image()
							->withFile($file)
							->resize(480, 480, true, 'heigth')
							->save($dirPhoto . '/' . $name2);
						$name3 = 'IMG_PARAM_L_' . $file->getRandomName();
						$image3 = \Config\Services::image()
							->withFile($file)
							->resize(144, 144, true, 'heigth')
							->save($dirPhoto . '/' . $name3);
						$dataParam = array(
							'parameterId'	=> json_decode($post['parameter'][$i])->parameterId,
							'assetId'		=> $assetId,
							'sortId'		=> $i + 1,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo1'		=> base_url() . '/' . $dirPhoto . '/' . $name1,
							'photo2'		=> base_url() . '/' . $dirPhoto . '/' . $name2,
							'photo3'		=> base_url() . '/' . $dirPhoto . '/' . $name3,
							'description'	=> json_decode($post['parameter'][$i])->description,
							'uom'			=> json_decode($post['parameter'][$i])->uom,
							'min'			=> json_decode($post['parameter'][$i])->min,
							'max'			=> json_decode($post['parameter'][$i])->max,
							'normal'		=> json_decode($post['parameter'][$i])->normal,
							'abnormal'		=> json_decode($post['parameter'][$i])->abnormal,
							'option'		=> json_decode($post['parameter'][$i])->option,
							'inputType'		=> json_decode($post['parameter'][$i])->inputType,
							'showOn'		=> json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
					} else {
						$dataParam = array(
							'parameterId'	=> json_decode($post['parameter'][$i])->parameterId,
							'assetId'		=> $assetId,
							'sortId'		=> $i + 1,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo1'		=> '',
							'photo2'		=> '',
							'photo3'		=> '',
							'description'	=> json_decode($post['parameter'][$i])->description,
							'uom'			=> json_decode($post['parameter'][$i])->uom,
							'min'			=> json_decode($post['parameter'][$i])->min,
							'max'			=> json_decode($post['parameter'][$i])->max,
							'normal'		=> json_decode($post['parameter'][$i])->normal,
							'abnormal'		=> json_decode($post['parameter'][$i])->abnormal,
							'option'		=> json_decode($post['parameter'][$i])->option,
							'inputType'		=> json_decode($post['parameter'][$i])->inputType,
							'showOn'		=> json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
					}
				}
			}

			$dtAsset = $assetModel->getAll(['assetId' => $assetId]);
			$activity = "Add Asset";
			if ($this->isJson($dtAsset[0]['description'])) {
				$dtAsset[0]['description'] = json_decode($dtAsset[0]['description']);
			}
			sendLog($activity, $assetId, json_encode($dtAsset));
			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success add asset',
				'data' => []
			));
		} catch (Exception $e) {
			return $this->response->setJSON(array(
				'status' => $e->getCode(),
				'message' => $e->getMessage(),
				'data' => []
			));
		}
	}

	public function detail($assetId)
	{
		if (!checkRoleList("MASTER.ASSET.DETAIL")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$model = new AssetModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();

		$adminId = $this->session->get('adminId');

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

		$tagging = $assetTaggingModel->where('assetId', $assetId)->orderBy('assetTaggingtype', 'asc')->findAll();
		$tagData = $this->db->table('tblm_tag')->where(['userId' => $adminId])->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->where(['deletedAt' => null, 'userId' => $adminId])->get()->getResult();
		$locationData = $this->db->table('tblm_tagLocation')->where(['userId' => $adminId])->get()->getResult();

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

		foreach ($assetParameter as $key => $value) {
			if ($value['min'] != null) {
				$assetParameter[$key]['min'] = (int) $value['min'];
				$assetParameter[$key]['max'] = (int) $value['max'];
			}
		}
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
		if (!checkRoleList("MASTER.ASSET.UPDATE")) {
			return $this->response->setJSON([
				'status' => 403,
				'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
		}

		$assetModel			= new AssetModel();
		$tagModel			= new TagModel();
		$tagLocationModel	= new TagLocationModel();
		$assetTagLocationModel	= new AssetTagLocationModel();
		$assetTagModel		= new AssetTagModel();
		$assetStatusModel	= new AssetStatusModel();
		$assetTaggingModel	= new AssetTaggingModel();
		$parameterModel		= new ParameterModel();
		$influxModel		= new LogModel();

		$post = $this->request->getPost();
		$assetId = $post['assetId'];
		$tag = $post['tag'];
		$adminId = $this->session->get('adminId');

		$beforeAsset = $assetModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId']]);
		$beforeParameter = $parameterModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId']]);

		try {
			// new tags and tag location
			$newTag = $post['tag'];
			if ($newTag != "") {
				$lengthNewTag = count($post['tag']);
				for ($i = 0; $i < $lengthNewTag; $i++) {
					$dataNewTag = array(
						'tagId' => json_decode($newTag[$i])->addTagId,
						'userId' => $this->session->get("adminId"),
						'tagName' => json_decode($newTag[$i])->addTagName,
						'description' => json_decode($newTag[$i])->addTagDesc
					);
					$tagModel->insert($dataNewTag);
				}
			}

			$newTagLocation = $post['location'];
			if ($newTagLocation != "") {
				$lengthNewTagLocation = count($post['location']);
				for ($i = 0; $i < $lengthNewTagLocation; $i++) {
					$dataNewTagLocation = array(
						'tagLocationId' => json_decode($newTagLocation[$i])->addLocationId,
						'userId' => $this->session->get("adminId"),
						'tagLocationName' => json_decode($newTagLocation[$i])->addLocationName,
						'latitude' => json_decode($newTagLocation[$i])->addLocationLatitude,
						'longitude' => json_decode($newTagLocation[$i])->addLocationLongitude,
						'description' => json_decode($newTagLocation[$i])->addLocationDesc,
					);
					$tagLocationModel->insert($dataNewTagLocation);
				}
			}

			$dirPath = 'upload/Asset';
			$fileAsset = $this->request->getFile('assetPhoto');
			$namePhoto = "";
			$dirPhoto = "";
			if ($fileAsset != null) {
				if (!is_dir($dirPath)) {
					mkdir($dirPath);
				}
				$dirPhoto = $dirPath . '/' . 'file' . $this->session->get('adminId');
				if (!is_dir($dirPhoto)) {
					mkdir($dirPhoto);
				}
				$namePhoto = 'IMG_' . $fileAsset->getRandomName();
				$image1 = \Config\Services::image()
					->withFile($fileAsset)
					->resize(480, 480, true, 'heigth')
					->save($dirPhoto . '/' . $namePhoto);
			}
			// asset
			$dataAsset = array(
				'assetId'		=> $post['assetId'],
				'assetName'		=> $post['assetName'],
				'assetNumber'	=> $post['assetNumber'],
				// 'photo'			=> $post['assetNumber'],
				'description'	=> $post['assetDesc'],
				'schManual'		=> $post['schManual'],
				'schType'		=> $post['schType'],
				'schFrequency'	=> $post['schFrequency'] == '' ? 1 : (int)$post['schFrequency'],
				'schWeekDays'	=> $post['schWeekDays'],
				'schWeeks'		=> $post['schWeeks'],
				'schDays'		=> $post['schDays'],
				'latitude'		=> $post['latitude'],
				'longitude'		=> $post['longitude'],
			);
			if ($fileAsset != null) {
				$dataAsset['photo'] = (base_url() . '/' . $dirPhoto . '/' . $namePhoto);
				if ($post['photo'] != 'null' && $post['photo'] != null && $post['photo'] != "") {
					$path = str_replace(base_url() . '/', "", $post['photo']);
					unlink($path);
				}
			} else {
				$check = $post['deleteAssetPhoto'] == 'true' ? true : false;
				if ($check) {
					$dataAsset['photo'] = null;
					$path = str_replace(base_url() . '/', "", $post['photo']);
					unlink($path);
				} else {
					$dataAsset['photo'] = $post['photo'];
				}
				if ($post['photo'] == 'null') {
					$dataAsset['photo'] = null;
				}
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
				$dataAsset['assetStatusId'] = $post['assetStatusId'];
			} else {
				$dataAsset['assetStatusId'] = $post['assetStatusId'];
			}
			$assetModel->update($assetId, $dataAsset);

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
				}
			} else {
				$assetTagLocationModel->deleteById($assetId);
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
				}
			} else {
				$assetTagModel->deleteById($assetId);
			}

			// asset tagging
			$lengthTagging = count($post['assetTagging']);
			if ($lengthTagging) {
				$getTagging = $post['assetTagging'];
				$existTagging = $assetTaggingModel->where('assetId', $assetId)->orderBy('assetTaggingtype', 'asc')->get()->getResult();
				if (count($existTagging)) {
					$assetTaggingModel->deleteById($assetId);
					for ($i = 0; $i < $lengthTagging; $i++) {
						$dataTagging = json_decode($getTagging[$i]);
						if ($dataTagging->assetTaggingValue != '') {
							$assetTaggingModel->insert($dataTagging);
						}
					}
				} else {
					for ($i = 0; $i < $lengthTagging; $i++) {
						$dataTagging = json_decode($getTagging[$i]);
						if ($dataTagging->assetTaggingValue != '') {
							$assetTaggingModel->insert($dataTagging);
						}
					}
				}
			}

			//delete parameter
			if ($post['deletedParameter'] != "") {
				$deletedParameter = explode(",", $post['deletedParameter']);
				foreach ($deletedParameter as $val) {
					$parameterModel->where(['assetId' => $assetId, 'parameterId' => $val])->delete();
				}
			}

			//edited parameter
			$dirPath = 'upload/Asset';
			if ($post['editedParameter'][0] != "") {
				$lengthEditedParameter = count($post['editedParameter']);
				for ($i = 0; $i < $lengthEditedParameter; $i++) {
					$file = $this->request->getFile('photo' . json_decode($post['editedParameter'][$i])->parameterId);
					if ($file != '') {
						$name1 = "";
						$name2 = "";
						$name3 = "";
						if (!is_dir($dirPath)) {
							mkdir($dirPath);
						}
						$dirPhoto = $dirPath . '/' . 'file' . $this->session->get('adminId');
						if (!is_dir($dirPhoto)) {
							mkdir($dirPhoto);
						}
						$name1 = 'IMG_PARAM_H_' . $file->getRandomName();
						$image1 = \Config\Services::image()
							->withFile($file)
							->save($dirPhoto . '/' . $name1);
						$name2 = 'IMG_PARAM_M_' . $file->getRandomName();
						$image2 = \Config\Services::image()
							->withFile($file)
							->resize(480, 480, true, 'heigth')
							->save($dirPhoto . '/' . $name2);
						$name3 = 'IMG_PARAM_L_' . $file->getRandomName();
						$image3 = \Config\Services::image()
							->withFile($file)
							->resize(144, 144, true, 'heigth')
							->save($dirPhoto . '/' . $name3);
						$dataEdited = json_decode($post['editedParameter'][$i]);
						$data = array(
							'parameterId'		=> $dataEdited->parameterId,
							'sortId'			=> $dataEdited->sortId,
							'parameterName'		=> $dataEdited->parameterName,
							'photo1' 			=> base_url() . '/' . $dirPhoto . '/' . $name1,
							'photo2' 			=> base_url() . '/' . $dirPhoto . '/' . $name2,
							'photo3' 			=> base_url() . '/' . $dirPhoto . '/' . $name3,
							'description'		=> $dataEdited->description,
							'uom'				=> $dataEdited->uom,
							'min'				=> $dataEdited->min,
							'max'				=> $dataEdited->max,
							'normal'			=> $dataEdited->normal,
							'abnormal'			=> $dataEdited->abnormal,
							'option'			=> $dataEdited->option,
							'inputType'			=> $dataEdited->inputType,
							'showOn'			=> $dataEdited->showOn,
						);
						$parameterModel->update($dataEdited->parameterId, $data);
						if ($dataEdited->photo1 != '' && $dataEdited->photo2 != '' && $dataEdited->photo3 != '') {
							$path1 = str_replace(base_url() . '/', "", $dataEdited->photo1);
							$path2 = str_replace(base_url() . '/', "", $dataEdited->photo2);
							$path3 = str_replace(base_url() . '/', "", $dataEdited->photo3);
							unlink($path1);
							unlink($path2);
							unlink($path3);
						}
					} else {
						$dataEdited = json_decode($post['editedParameter'][$i]);
						$data = array(
							'parameterId'		=> $dataEdited->parameterId,
							'sortId'			=> $dataEdited->sortId,
							'parameterName'		=> $dataEdited->parameterName,
							'photo1'			=> $dataEdited->deletePhoto == true ? '' : $dataEdited->photo1,
							'photo2'			=> $dataEdited->deletePhoto == true ? '' : $dataEdited->photo2,
							'photo3'			=> $dataEdited->deletePhoto == true ? '' : $dataEdited->photo3,
							'description'		=> $dataEdited->description,
							'uom'				=> $dataEdited->uom,
							'min'				=> $dataEdited->min,
							'max'				=> $dataEdited->max,
							'normal'			=> $dataEdited->normal,
							'abnormal'			=> $dataEdited->abnormal,
							'option'			=> $dataEdited->option,
							'inputType'			=> $dataEdited->inputType,
							'showOn'			=> $dataEdited->showOn,
						);
						$parameterModel->update($dataEdited->parameterId, $data);
						if ($dataEdited->deletePhoto) {
							$path1 = str_replace(base_url() . '/', "", $dataEdited->photo1);
							$path2 = str_replace(base_url() . '/', "", $dataEdited->photo2);
							$path3 = str_replace(base_url() . '/', "", $dataEdited->photo3);
							unlink($path1);
							unlink($path2);
							unlink($path3);
						}
					}
				}
			}

			// insert parameter
			$lengthParam = count($post['parameter']);
			if ($post['parameter'][0] != '') {
				for ($i = 0; $i < $lengthParam; $i++) {
					$file = $this->request->getFile('photo' . json_decode($post['parameter'][$i])->parameterId);
					if ($file != '') {
						$name1 = "";
						$name2 = "";
						$name3 = "";
						if (!is_dir($dirPath)) {
							mkdir($dirPath);
						}
						$dirPhoto = $dirPath . '/' . 'file' . $this->session->get('adminId');
						if (!is_dir($dirPhoto)) {
							mkdir($dirPhoto);
						}
						$name1 = 'IMG_PARAM_H_' . $file->getRandomName();
						$image1 = \Config\Services::image()
							->withFile($file)
							->save($dirPhoto . '/' . $name1);
						$name2 = 'IMG_PARAM_M_' . $file->getRandomName();
						$image2 = \Config\Services::image()
							->withFile($file)
							->resize(480, 480, true, 'heigth')
							->save($dirPhoto . '/' . $name2);
						$name3 = 'IMG_PARAM_L_' . $file->getRandomName();
						$image3 = \Config\Services::image()
							->withFile($file)
							->resize(144, 144, true, 'heigth')
							->save($dirPhoto . '/' . $name3);
						$dataParam = array(
							'parameterId'	=> json_decode($post['parameter'][$i])->parameterId,
							'assetId'		=> $assetId,
							'sortId'		=> (json_decode($post['parameter'][$i])->sortId) == "null" || "" || "0" ? null : json_decode($post['parameter'][$i])->sortId,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo1'		=> base_url() . '/' . $dirPhoto . '/' . $name1,
							'photo2'		=> base_url() . '/' . $dirPhoto . '/' . $name2,
							'photo3'		=> base_url() . '/' . $dirPhoto . '/' . $name3,
							'description'	=> json_decode($post['parameter'][$i])->description,
							'uom'			=> json_decode($post['parameter'][$i])->uom,
							'min'			=> json_decode($post['parameter'][$i])->min,
							'max'			=> json_decode($post['parameter'][$i])->max,
							'normal'		=> json_decode($post['parameter'][$i])->normal,
							'abnormal'		=> json_decode($post['parameter'][$i])->abnormal,
							'option'		=> json_decode($post['parameter'][$i])->option,
							'inputType'		=> json_decode($post['parameter'][$i])->inputType,
							'showOn'		=> json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
					} else {
						$dataParam = array(
							'parameterId'	=> json_decode($post['parameter'][$i])->parameterId,
							'assetId'		=> $assetId,
							'sortId'		=> (json_decode($post['parameter'][$i])->sortId) == "null" ? null : json_decode($post['parameter'][$i])->sortId,
							'parameterName' => json_decode($post['parameter'][$i])->parameterName,
							'photo1'		=> '',
							'photo2'		=> '',
							'photo3'		=> '',
							'description'	=> json_decode($post['parameter'][$i])->description,
							'uom'			=> json_decode($post['parameter'][$i])->uom,
							'min'			=> json_decode($post['parameter'][$i])->min,
							'max'			=> json_decode($post['parameter'][$i])->max,
							'normal'		=> json_decode($post['parameter'][$i])->normal,
							'abnormal'		=> json_decode($post['parameter'][$i])->abnormal,
							'option'		=> json_decode($post['parameter'][$i])->option,
							'inputType'		=> json_decode($post['parameter'][$i])->inputType,
							'showOn'		=> json_decode($post['parameter'][$i])->showOn,
						);
						$parameterModel->insert($dataParam);
					}
				}
			}

			$afterAsset = $assetModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId']]);
			$afterParameter = $parameterModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId']]);

			$body = [
				'data_before' => $beforeAsset[0],
				'data_after' => $afterAsset[0]
			];
			$cekDescBefore = json_decode($body['data_before']['description']);
			if ($cekDescBefore != null) {
				$body['data_before']['description'] = json_decode($body['data_before']['description']);
			}
			$cekDescAfter = json_decode($body['data_after']['description']);
			if ($cekDescAfter != null) {
				$body['data_after']['description'] = json_decode($body['data_after']['description']);
			}
			$bodyP = [
				'data_before' => $beforeParameter[0],
				'data_after' => $afterParameter[0]
			];

			$activity1	= 'Update asset';
			$activity2	= 'Update parameter';

			sendLog($activity1, $assetId, json_encode($body));
			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success update data',
				'data' => []
			));
		} catch (Exception $e) {
			return $this->response->setJSON(array(
				'status' => $e->getCode(),
				'message' => $e->getMessage(),
				'data' => []
			));
		}
	}

	public function delete()
	{
		if (!checkRoleList("MASTER.ASSET.DELETE")) {
			return $this->response->setJSON([
				'status' => 403,
				'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
		}

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

	public function downloadSampleParameter()
	{
		if (!checkRoleList("MASTER.ASSET.PARAMETER.IMPORT.SAMPLE")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		return $this->response->download($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . 'download/sampleImportParameter.xlsx', null);
	}

	public function getDataImportParameter()
	{
		$file = $this->request->getFile('importParam');
		if ($file) {
			$newName = "doc" . time() . '.xlsx';
			$file->move('upload/', $newName);
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('upload/' . $newName);
			$dataImport = [];
			foreach ($reader->getSheetIterator() as $sheet) {
				$numrow = 1;
				foreach ($sheet->getRowIterator() as $row) {
					if ($numrow > 1) {
						// if ($row->getCellAtIndex(1) != '' && $row->getCellAtIndex(2) != '') {
						$dataImport[] = array(
							'no' => $numrow + 1,
							'parameterName' => $row->getCellAtIndex(1)->getValue(),
							'description' => $row->getCellAtIndex(2)->getValue(),
							'max' => $row->getCellAtIndex(3)->getValue() < $row->getCellAtIndex(4)->getValue() == true ? $row->getCellAtIndex(4)->getValue() : $row->getCellAtIndex(3)->getValue(),
							'min' => $row->getCellAtIndex(4)->getValue() > $row->getCellAtIndex(3)->getValue() == true ? $row->getCellAtIndex(3)->getValue() : $row->getCellAtIndex(4)->getValue(),
							'normal' => $row->getCellAtIndex(5)->getValue(),
							'abnormal' => $row->getCellAtIndex(6)->getValue(),
							'option' => $row->getCellAtIndex(8)->getValue(),
							'uom' => $row->getCellAtIndex(7)->getValue(),
							'inputType' => $row->getCellAtIndex(9)->getValue(),
							'showOn' => $row->getCellAtIndex(10)->getValue(),
							'flipMax' => $row->getCellAtIndex(3)->getValue() < $row->getCellAtIndex(4)->getValue() ? true : false,
							'flipMin' => $row->getCellAtIndex(4)->getValue() > $row->getCellAtIndex(3)->getValue() ? true : false,
						);
						// } else {
						// 	return $this->response->setJSON(array('status' => 'failed', 'message' => 'Data Does Not Match'));
						// }
					}
					$numrow++;
				}
			}
			if ($dataImport) {
				unlink('upload/' . $newName);
				return $this->response->setJSON(array('status' => 'success', 'message' => '', 'data' => $dataImport));
			} else {
				return $this->response->setJSON(array('status' => 'failed', 'message' => 'Data Not Found!'));
			}
		} else {
			return $this->response->setJSON((array('status' => 'failed', 'message' => 'Bad Request!')));
		}
	}

	public function sortingParameter()
	{
		if (!checkRoleList("MASTER.ASSET.PARAMETER.SORT")) {
			return $this->response->setJSON([
				'status' => 403,
				'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
		}

		$parameterModel = new ParameterModel();
		$json = $this->request->getJSON();
		$data = $json->data;
		if (empty($data)) {
			return $this->respond->setJson([
				'status' => 404,
				'message' => "Data is empty!",
				'data' => ''
			], 404);
		}
		try {
			foreach ($data as $key => $value) {
				$dataSort = array(
					'sortId' => $value[0]
				);
				$parameterModel->update($value[1], $dataSort);
			}
			$assetId = $json->assetId;
			$activity = "Sorting parameter";
			$adminId = $this->session->get('adminId');
			$dtParameter = $parameterModel->getAll(['userId' => $adminId]);
			sendLog($activity, $assetId, json_encode($dtParameter));
			return $this->response->setJson([
				'status' => 200,
				'message' => "Successfully Updated Data",
				'data' => $data
			], 200);
		} catch (Exception $e) {
			throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function downloadSampleAsset()
	{
		if (!checkRoleList("MASTER.ASSET.PARAMETER.IMPORT.SAMPLE")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		return $this->response->download($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . '/download/SampleImportAsset.xlsx', null);
	}

	public function import()
	{
		$data = array(
			'title' => 'Import Asset',
			'subtitle' => 'Import Asset',
		);
		$data["breadcrumbs"] = [
			[
				"title"    => "Home",
				"link"    => "Dashboard"
			],
			[
				"title"    => "Asset",
				"link"    => "Asset"
			],
			[
				'title' => "Import",
			]
		];

		return $this->template->render('Master/Asset/import', $data);
	}

	public function getDataImport()
	{
		$file = $this->request->getFile('importAsset');
		if ($file) {
			$name = 'docAsset' . time() . '.xlsx';
			$file->move('upload/', $name);
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('upload/' . $name);
			$dataAsset = [];
			$parameter = [];
			$desc = [];
			foreach ($reader->getSheetIterator() as $sheet) {
				$rowAsset = 1;
				if ($sheet->getName() == 'Asset') {
					foreach ($sheet->getRowIterator() as $row) {
						if ($rowAsset > 1) {
							$dataAsset[] = array(
								'assetName' => $row->getCellAtIndex(0)->getValue(),
								'assetNumber' => $row->getCellAtIndex(1)->getValue(),
								'description' => $row->getCellAtIndex(2)->getValue(),
								'tagLocation' => $row->getCellAtIndex(3)->getValue(),
								'tag' => $row->getCellAtIndex(4)->getValue(),
								'schManual' => $row->getCellAtIndex(5)->getValue(),
								'schType' => $row->getCellAtIndex(6)->getValue(),
								'schFrequency' => $row->getCellAtIndex(7)->getValue(),
								'schWeeks' => $row->getCellAtIndex(10)->getValue(),
								'schWeekDays' => $row->getCellAtIndex(6)->getValue() === "Monthly" ? $row->getCellAtIndex(11)->getValue() : $row->getCellAtIndex(8)->getValue(),
								'schDays' => $row->getCellAtIndex(9)->getValue(),
								'rfid' => $row->getCellAtIndex(12)->getValue(),
								'coordinat' => str_replace("'", '', $row->getCellAtIndex(13)->getValue()),
								'assetStatus' => $row->getCellAtIndex(14)->getValue()
							);
						}
						$rowAsset++;
					}
				}
				$rowDescription = 1;
				if ($sheet->getIndex() == 1) {
					$description = [];
					$special = [];
					foreach ($sheet->getRowIterator() as $row) {
						if ($rowDescription > 2) {
							$assetNumber = $row->getCellAtIndex(0)->getValue();
							$key = $row->getCellAtIndex(1)->getValue();
							$value = $row->getCellAtIndex(2)->getValue();
							$description[] = array("assetNumber" => $assetNumber, "key" => $key, "value" => $value);
						}
						$rowDescription++;
					}
					// var_dump($description);
					foreach ($dataAsset as $i => $items) {
						$descAssetAll = [];
						$descAsset = [];
						foreach ($description as $index => $val) {
							$assetNumber = $val['assetNumber'];
							$key = $val['key'];
							$value = $val['value'];
							if ($assetNumber == "all") {
								$descAssetAll[] = array("key" => $key, "value" => $value);
							} else {
								$cekAssetNumber = array_filter($dataAsset, function ($items) use ($assetNumber) {
									return $items['assetNumber'] = $assetNumber;
								});
								if ($cekAssetNumber) {
									$descAsset[] = array('assetNumber' => $assetNumber, "key" => $key, "value" => $value);
								}
							}
						}
						foreach ($description as $index => $rows) {
							$assetNumber = $rows['assetNumber'];
							if ($dataAsset[$i]['assetNumber'] != $assetNumber) {
								if ($dataAsset[$i]['description'] == "") {
									$dataAsset[$i]['description'] = $descAssetAll;
								}
							}
						}
					}
					foreach ($descAsset as $descKey => $descValue) {
						foreach ($dataAsset as $a => $assetVal) {
							$cekIsString = is_string($dataAsset[$a]['description']);
							if ($descValue['assetNumber'] == $assetVal['assetNumber']) {
								$valkey = $descValue['key'];
								$valVal = $descValue['value'];
								if (!$cekIsString) {
									array_push($dataAsset[$a]['description'], array("key" => $valkey, "value" => $valVal));
								}
							}
						}
					}
					foreach ($dataAsset as $b => $bVal) {
						$isString = is_string($bVal['description']);
						if (!$isString) {
							$dataAsset[$b]['description'] = json_encode($dataAsset[$b]['description']);
						}
					}
				}

				$rowParameter = 1;
				if ($sheet->getIndex() == 2) {
					foreach ($sheet->getRowIterator() as $row) {
						if ($rowParameter > 1) {
							$parameter[] = array(
								// 'parameterId' => uuidv4(),
								'sortId' => $row->getCellAtIndex(0)->getValue(),
								'parameterName' => $row->getCellAtIndex(1)->getValue(),
								'description' => $row->getCellAtIndex(2)->getValue(),

								'max' => $row->getCellAtIndex(3)->getValue() ? $row->getCellAtIndex(3)->getValue() : null,
								'min' => $row->getCellAtIndex(4)->getValue() ? $row->getCellAtIndex(4)->getValue() : null,
								'normal' => $row->getCellAtIndex(5)->getValue() ? $row->getCellAtIndex(5)->getValue() : "",
								'abnormal' => $row->getCellAtIndex(6)->getValue() ? $row->getCellAtIndex(6)->getValue() : "",
								'option' => $row->getCellAtIndex(8)->getValue() ? $row->getCellAtIndex(8)->getValue() : "",
								'uom' => $row->getCellAtIndex(7)->getValue() ? $row->getCellAtIndex(7)->getValue() : "",

								'inputType' => $row->getCellAtIndex(9)->getValue(),
								'showOn' => $row->getCellAtIndex(10)->getValue(),
							);
						}
						$rowParameter++;
					}
				}
			}
			$reader->close();
			$data['dataAsset'] = $dataAsset;
			$data['parameter'] = $parameter;
			unlink('upload/' . $name);
			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success Reading Data.',
				'data' => $data,
			));
		} else {
			return $this->response->setJSON(array(
				'status' => 500,
				'message' => 'Bad Request!'
			));
		}
		die();
	}

	public function importAsset()
	{
		$assetModel = new AssetModel();
		$tagModel = new TagModel();
		$tagLocationModel = new TagLocationModel();
		$assetTagModel = new AssetTagModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$assetStatusModel = new AssetStatusModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();
		$asset = $this->request->getPost('dataAsset');
		$parameter = $this->request->getPost('parameter');
		$lengthAsset = count($asset);
		try {
			$dataAsset = "";
			foreach ($asset as $key => $value) {
				$row = json_decode($value);
				$dataAsset = $row;
				break;
			}
			for ($i = 0; $i < $lengthAsset; $i++) {
				$dataInsert = array(
					'assetId' => uuidv4(),
					'userId' => $this->session->get("adminId"),
					'assetName' => $dataAsset[$i]->assetName,
					'assetNumber' => $dataAsset[$i]->assetNumber,
					'description' => $dataAsset[$i]->description,
					'schManual' => $dataAsset[$i]->schManual == "Automatic" ? 0 : 1,
					'schType' => $dataAsset[$i]->schType,
					'schFrequency' => $dataAsset[$i]->schFrequency,
					'schWeekDays' => $dataAsset[$i]->schWeekDays,
					'schWeeks' => $dataAsset[$i]->schType == 'Monthly' ? $dataAsset[$i]->schWeeks : '',
					'schDays' => $dataAsset[$i]->schType == 'Monthly' ? $dataAsset[$i]->schDays : '',
				);

				// asset status
				$status = $dataAsset[$i]->assetStatus;
				$dataStatus = $assetStatusModel->getByName($status);
				if ($dataStatus == NULL) {
					$newStatus = array(
						'assetStatusId' => uuidv4(),
						'userId' => $this->session->get("adminId"),
						'assetStatusName' => $status,
					);
					$assetStatusModel->insert($newStatus);
					$dataInsert['assetStatusId'] = $newStatus['assetStatusId'];
				} else {
					$dataInsert['assetStatusId'] = $dataStatus['assetStatusId'];
				}
				$assetModel->insert($dataInsert);

				//parameter
				$dataParameter = "";
				foreach ($parameter as $key => $value) {
					$dataParameter = json_decode($value);
				}
				foreach ($dataParameter as $key => $value) {
					$arrParameter = (array) $value;
					$dataParameter['parameterId'] = uuidv4();
					$arrParameter['assetId'] = $dataInsert['assetId'];
					$parameterModel->insert($arrParameter);
				}

				// tag
				$tag = $dataAsset[$i]->tag;
				// $arrTag = explode(",", $tag);
				foreach ($tag as $key => $val) {
					$dataTag = $tagModel->getByName($val);
					if ($dataTag == NULL) {
						$newTag = array(
							'tagId' => uuidv4(),
							'userId' => $this->session->get("adminId"),
							'tagName' => $val,
							'description' => ''
						);
						$tagModel->insert($newTag);
						$insertNewAssetTag = array(
							'assetTagId' => uuidv4(),
							'assetId' => $dataInsert['assetId'],
							'tagId' => $newTag['tagId']
						);
						$assetTagModel->insert($insertNewAssetTag);
					} else {
						$tagId = $dataTag['tagId'];
						$insertAssetTag = array(
							'assetTagId' => uuidv4(),
							'assetId' => $dataInsert['assetId'],
							'tagId' => $tagId
						);
						$assetTagModel->insert($insertAssetTag);
					}
				}
				// tag location
				$tagLocation = $dataAsset[$i]->tagLocation;
				// $arrTagLocation = explode(",", $tagLocation);
				foreach ($tagLocation as $key => $val) {
					$dataTagLocation = $tagLocationModel->getByName($val);
					if ($dataTagLocation == NULL) {
						$newTagLocation = array(
							'tagLocationId' => uuidv4(),
							'userId' => $this->session->get("adminId"),
							'tagLocationName' => $val,
							'latitude' => '',
							'longitude' => '',
							'description' => '',
						);
						$tagLocationModel->insert($newTagLocation);
						$insertNewAssetTagLocation = array(
							'assetTagLocationId' => uuidv4(),
							'assetId' => $dataInsert['assetId'],
							'tagLocationId' => $newTagLocation['tagLocationId']
						);
						$assetTagLocationModel->insert($insertNewAssetTagLocation);
					} else {
						$tagLocationId = $dataTagLocation['tagLocationId'];
						$insertAssetTagLocation = array(
							'assetTagLocationId' => uuidv4(),
							'assetId' => $dataInsert['assetId'],
							'tagLocationId' => $tagLocationId
						);
						$assetTagLocationModel->insert($insertAssetTagLocation);
					}
				}

				// asset tagging
				$rfid = $dataAsset[$i]->rfid;
				if ($rfid != "") {
					$dataRFID = array(
						'assetTaggingId' => uuidv4(),
						'assetId' => $dataInsert['assetId'],
						'assetTaggingValue' => $rfid,
						'assetTaggingtype' => 'rfid'
					);
					$assetTaggingModel->insert($dataRFID);
				}
				$coordinat = $dataAsset[$i]->coordinat;
				if ($coordinat != "") {
					$dataCoordinat = array(
						'assetTaggingId' => uuidv4(),
						'assetId' => $dataInsert['assetId'],
						'assetTaggingValue' => $coordinat,
						'assetTaggingtype' => 'coordinat'
					);
					$assetTaggingModel->insert($dataCoordinat);
				}
			}
			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Successfully Import Asset',
				'data' => ''
			));
		} catch (Exception $e) {
			throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
		die();
	}

	public function export()
	{
		$writer = WriterEntityFactory::createXLSXWriter();
		$assetModel = new AssetModel();
		$adminId = $this->session->get('adminId');

		$dataAsset = $assetModel->getAll(['userId' => $adminId, 'deletedAt' => null]);

		$writer->setShouldUseInlineStrings(true);
		$header = ["No", "Asset Name", "Asset Number", "Asset Status", "Tag", "Tag Location"];
		$styleHeader = (new StyleBuilder())
			->setCellAlignment(CellAlignment::CENTER)
			->setBackgroundColor(COLOR::YELLOW)
			->build();
		$styleBody = (new StyleBuilder())
			->setCellAlignment(CellAlignment::LEFT)
			->build();
		$dataArr = [];

		if (count($dataAsset)) {
			foreach ($dataAsset as $key => $value) {
				$arr = [$key + 1, $value['assetName'], $value['assetNumber'], $value['assetStatusName'], $value['tagName'], $value['tagLocationName']];
				array_push($dataArr, $arr);
			}
		}
		$fileName = "Asset - " . date("d M Y") . '.xlsx';
		$writer->openToBrowser($fileName);

		$rowFromValues = WriterEntityFactory::createRowFromArray($header, $styleHeader);
		$writer->addRow($rowFromValues);
		foreach ($dataArr as $key => $value) {
			$rowFromValues = WriterEntityFactory::createRowFromArray($value, $styleBody);
			$writer->addRow($rowFromValues);
		}
		$writer->close();
	}

	private function isJson(string $value)
	{
		try {
			json_decode($value, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}
}
