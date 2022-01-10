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
use App\Models\Wizard\SubscriptionModel;
use App\Models\Wizard\TransactionModel;
use CodeIgniter\API\ResponseTrait;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use DateTime;
use Exception;
use Faker\Provider\Uuid;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PHPUnit\Framework\Constraint\IsJson;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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

	public function detail($assetId)
	{
		if (!checkRoleList("MASTER.ASSET.DETAIL")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$model = new AssetModel();
		$assetTaggingModel = new AssetTaggingModel();
		$subscriptionModel = new SubscriptionModel();
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
		$subscriptionData = $subscriptionModel->getAll(['userId' => $adminId]);
		$parameterData = $parameterModel->getAll(['userId' => $adminId]);

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
		$data['subscription']	= $subscriptionData;
		$data['parameterData']	= $parameterData;

		return $this->template->render('Master/Asset/detail2', $data);
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
		$activity2 = 'Update parameter';

		try {
			$data = $influxModel->getLogAsset($activity, $activity2, $adminId, $assetId, $datestart, $dateend);
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

		if (!checkLimitAsset()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your assets has reached the limit"]);
		}

		$modelAsset = new AssetModel();
		$parameterModel = new ParameterModel();
		$tagModel = new TagModel();
		$tagLocationModel = new TagLocationModel();
		$subscriptionModel = new SubscriptionModel();
		$assetStatusModel = new AssetStatusModel();

		$adminId = $this->session->get('adminId');

		$locationData = $this->db->table('tblm_tagLocation')->where(['userId' => $adminId])->get()->getResult();
		$tagData = $this->db->table('tblm_tag')->where(['userId' => $adminId])->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->where(['deletedAt' => null, 'userId' => $adminId])->get()->getResult();
		$subscriptionData	= $subscriptionModel->getAll(['userId' => $adminId]);
		$parameterData		= $parameterModel->getAll(['userId' => $adminId]);
		$assetStatus = $assetStatusModel->getAll(['userId' => $adminId, 'deletedAt' => null]);

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
		$data['subscription'] = $subscriptionData;
		$data['parameterData'] = $parameterData;
		$data['assetStatus'] = $assetStatus;
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
		if (!checkLimitAsset()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your assets has reached the limit"]);
		}

		$assetModel = new AssetModel();
		$tagModel	= new TagModel();
		$tagLocationModel	= new TagLocationModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$assetTagModel = new AssetTagModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();

		$adminId = $this->session->get('adminId');


		$post = $this->request->getPost();
		$assetId = $post['assetId'];

		$checkAssetNumber = $assetModel->getAll(['userId' => $adminId, 'assetNumber' => $post['assetNumber']]);
		if (count($checkAssetNumber)) {
			$post['assetNumber'] = $post['assetNumber'] . '_' . $this->randomString();
		}
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
			// tag and location new
			if ($post['tag'] != '') {
				$lengthAddTag = count($post['tag']);
				if ($lengthAddTag > 0) {
					for ($i = 0; $i < $lengthAddTag; $i++) {
						if (checkLimitTag()) {
							$dataAddTag = array(
								'tagId'			=> json_decode($post['tag'][$i])->addTagId,
								'userId'		=> $this->session->get("adminId"),
								'tagName'		=> json_decode($post['tag'][$i])->addTagName,
								'description'	=> json_decode($post['tag'][$i])->addTagDesc,
							);
							$tagModel->insert($dataAddTag);
							sendLog("Add tag", $assetId, json_encode($dataAddTag));
						}
					}
				}
			}
			if ($post['location'] != '') {
				$lengthAddLocation = count($post['location']);
				if ($lengthAddLocation > 0) {
					for ($i = 0; $i < $lengthAddLocation; $i++) {
						if (checkLimitTagLocation()) {
							$dataAddLocation = array(
								'tagLocationId'		=> json_decode($post['location'][$i])->addLocationId,
								'userId'			=> $this->session->get("adminId"),
								'tagLocationName'	=> json_decode($post['location'][$i])->addLocationName,
								'latitude'			=> json_decode($post['location'][$i])->addLocationLatitude,
								'longitude'			=> json_decode($post['location'][$i])->addLocationLongitude,
								'description'		=> json_decode($post['location'][$i])->addLocationDesc,
							);
							$tagLocationModel->insert($dataAddLocation);
							sendLog("Add tag", $assetId, json_encode($dataAddLocation));
						}
					}
				}
			}

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
				}
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
				}
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
					if (checkLimitParameter()) {
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
								'userId'		=> $adminId,
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
								'userId'		=> $adminId,
								'sortId'		=> $i + 1,
								'parameterName' => json_decode($post['parameter'][$i])->parameterName,
								'photo1'		=> null,
								'photo2'		=> null,
								'photo3'		=> null,
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

	public function saveSetting()
	{
		if (!checkRoleList("MASTER.ASSET.UPDATE")) {
			return $this->response->setJSON([
				'status' => 403,
				'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
		}
		if (!checkLimitAsset()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your assets has reached the limit"]);
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

		$checkAssetNumber = $assetModel->getAll(['userId' => $adminId, 'assetNumber' => $post['assetNumber']]);
		$beforeAsset = $assetModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId']]);
		$beforeParameter = $parameterModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId'], 'deletedAt' => null]);

		if (count($checkAssetNumber)) {
			foreach ($checkAssetNumber as $key => $value) {
				if (!($value['assetId'] == $post['assetId'])) {
					$post['assetNumber'] = $post['assetNumber'] . '_' . $this->randomString();
				}
			}
		}

		try {
			// new tags and tag location
			$newTag = $post['tag'];
			if ($newTag != "") {
				$lengthNewTag = count($post['tag']);
				for ($i = 0; $i < $lengthNewTag; $i++) {
					if (checkLimitTag()) {
						$dataNewTag = array(
							'tagId' => json_decode($newTag[$i])->addTagId,
							'userId' => $this->session->get("adminId"),
							'tagName' => json_decode($newTag[$i])->addTagName,
							'description' => json_decode($newTag[$i])->addTagDesc
						);
						$tagModel->insert($dataNewTag);
						sendLog("Add tag", $assetId, json_encode($dataNewTag));
					}
				}
			}

			$newTagLocation = $post['location'];
			if ($newTagLocation != "") {
				$lengthNewTagLocation = count($post['location']);
				for ($i = 0; $i < $lengthNewTagLocation; $i++) {
					if (checkLimitTagLocation()) {
						$dataNewTagLocation = array(
							'tagLocationId' => json_decode($newTagLocation[$i])->addLocationId,
							'userId' => $this->session->get("adminId"),
							'tagLocationName' => json_decode($newTagLocation[$i])->addLocationName,
							'latitude' => json_decode($newTagLocation[$i])->addLocationLatitude,
							'longitude' => json_decode($newTagLocation[$i])->addLocationLongitude,
							'description' => json_decode($newTagLocation[$i])->addLocationDesc,
						);
						$tagLocationModel->insert($dataNewTagLocation);
						sendLog("Add tag location", $assetId, json_encode($dataNewTag));
					}
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
					$dataParameterDel = $parameterModel->getAll(['assetId' => $assetId, 'parameterId' => $val, 'deletedAt' => null]);
					foreach ($dataParameterDel as $key => $value) {
						if ($value['photo1'] != "" && $value['photo1'] != null && $value['photo1'] != "null" && $value['photo2'] != "" && $value['photo2'] != null && $value['photo2'] != "null" && $value['photo3'] != "" && $value['photo3'] != null && $value['photo3'] != "null") {
							$path1 = str_replace(base_url() . '/', "", $value['photo1']);
							$path2 = str_replace(base_url() . '/', "", $value['photo2']);
							$path3 = str_replace(base_url() . '/', "", $value['photo3']);
							unlink($path1);
							unlink($path2);
							unlink($path3);
						}
					}
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
							'photo1'			=> $dataEdited->deletePhoto == true ? null : $dataEdited->photo1,
							'photo2'			=> $dataEdited->deletePhoto == true ? null : $dataEdited->photo2,
							'photo3'			=> $dataEdited->deletePhoto == true ? null : $dataEdited->photo3,
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
					if (checkLimitParameter()) {
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
								'photo1'		=> null,
								'photo2'		=> null,
								'photo3'		=> null,
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
			}

			$afterAsset = $assetModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId']]);
			$afterParameter = $parameterModel->getAll(['userId' => $adminId, 'assetId' => $post['assetId'], 'deletedAt' => null]);

			$body = [
				'data_before' => $beforeAsset[0],
				'data_after' => $afterAsset[0]
			];
			$bodyParameter = [
				'data_before' => $beforeParameter,
				'data_after' => $afterParameter
			];

			$cekDescBefore = json_decode($body['data_before']['description']);
			if ($cekDescBefore != null) {
				$body['data_before']['description'] = json_decode($body['data_before']['description']);
			}
			$cekDescAfter = json_decode($body['data_after']['description']);
			if ($cekDescAfter != null) {
				$body['data_after']['description'] = json_decode($body['data_after']['description']);
			}

			$activity1	= 'Update asset';
			$activity2	= 'Update parameter';

			sendLog($activity1, $assetId, json_encode($body));
			sendLog($activity2, $assetId, json_encode($bodyParameter));
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

		$assetModel = new AssetModel();
		$parameterModel = new ParameterModel();
		$assetTagModel = new AssetTagModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$assetTaggingModel = new AssetTaggingModel();

		$adminId = $this->session->get('adminId');
		$json  = $this->request->getJSON();
		$assetId = $json->assetId;

		$dataAsset = $assetModel->getAll(['userId' => $adminId, 'assetId' => $assetId]);
		$dataParameter = $parameterModel->getAll(['userId' => $adminId, 'assetId' => $assetId, 'deletedAt' => null]);
		$dataAssetTag = $assetTagModel->getAll(['assetId' => $assetId]);
		$dataAssetTagLocation = $assetTagLocationModel->getAll(['assetId' => $assetId]);
		$dataAssetTagging = $assetTaggingModel->getAll(['assetId' => $assetId]);

		if (empty($dataAsset)) {
			return $this->response->setJSON(array(
				'status' => 404,
				'message' => 'Data not found',
				'data' => []
			));
		}

		try {

			if (!empty($dataParameter)) {
				foreach ($dataParameter as $key => $value) {
					$parameterModel->delete(['parameterId' => $value['parameterId']]);
					if ($value['photo1'] != "" && $value['photo1'] != null && $value['photo1'] != "null" && $value['photo2'] != "" && $value['photo2'] != null && $value['photo2'] != "null" && $value['photo3'] != "" && $value['photo3'] != null && $value['photo3'] != "null") {
						$path1 = str_replace(base_url() . '/', "", $value['photo1']);
						$path2 = str_replace(base_url() . '/', "", $value['photo2']);
						$path3 = str_replace(base_url() . '/', "", $value['photo3']);
						unlink($path1);
						unlink($path2);
						unlink($path3);
					}
				}
			}

			if (!empty($dataAssetTag)) {
				foreach ($dataAssetTag as $key => $value) {
					$assetTagModel->delete(['assetTagId' => $value['assetTagId']]);
				}
			}

			if (!empty($dataAssetTagLocation)) {
				foreach ($dataAssetTagLocation as $key => $value) {
					$assetTagLocationModel->delete(['assetTagLocationId' => $value['assetTagLocationId']]);
				}
			}

			if (!empty($dataAssetTagging)) {
				foreach ($dataAssetTagging as $key => $value) {
					$assetTaggingModel->delete(['assetTaggingId' => $value['assetTaggingId']]);
				}
			}

			$assetModel->delete(['assetId' => $assetId]);

			if ($this->isJson($dataAsset[0]['description'])) {
				$dataAsset[0]['description'] = json_decode($dataAsset[0]['description']);
			}
			if ($dataAsset[0]['photo'] != null && $dataAsset[0]['photo'] != "" && $dataAsset[0]['photo'] != 'null') {
				$path = str_replace(base_url() . '/', "", $dataAsset[0]['photo']);
				unlink($path);
			}
			sendLog('Delete asset', $assetId, json_encode($dataAsset));
			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success delete data',
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
							'max' => $row->getCellAtIndex(3)->getValue() < $row->getCellAtIndex(4)->getValue() == true ? ($row->getCellAtIndex(4)->getValue() === "" ? null : $row->getCellAtIndex(4)->getValue()) : ($row->getCellAtIndex(3)->getValue() === "" ? null : $row->getCellAtIndex(3)->getValue()),
							'min' => $row->getCellAtIndex(4)->getValue() > $row->getCellAtIndex(3)->getValue() == true ? ($row->getCellAtIndex(3)->getValue() === "" ? null : $row->getCellAtIndex(3)->getValue()) : ($row->getCellAtIndex(4)->getValue() === "" ? null : $row->getCellAtIndex(4)->getValue()),
							'normal' => $row->getCellAtIndex(5)->getValue(),
							'abnormal' => $row->getCellAtIndex(6)->getValue(),
							'option' => $row->getCellAtIndex(8)->getValue(),
							'uom' => $row->getCellAtIndex(7)->getValue(),
							'inputType' => $row->getCellAtIndex(9)->getValue(),
							'showOn' => $row->getCellAtIndex(10)->getValue(),
							'flipMax' => $row->getCellAtIndex(3)->getValue() < $row->getCellAtIndex(4)->getValue() ? true : false,
							'flipMin' => $row->getCellAtIndex(4)->getValue() > $row->getCellAtIndex(3)->getValue() ? true : false,
							'photo' => "",
							'photo1' => null,
							'photo2' => null,
							'photo3' => null,
							'status' => 'New'
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
		if (!checkLimitAsset()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your assets has reached the limit"]);
		}
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

		if (!checkLimitAsset()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your assets has reached the limit"]);
		}

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

	public function duplicate()
	{
		$assetModel = new AssetModel();
		$assetTaggingModel = new AssetTaggingModel();
		$assetTagModel = new AssetTagModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$parameterModel = new ParameterModel();

		$assetId = $this->request->getPost('assetId');
		$adminId = $this->session->get('adminId');

		$newAssetId = uuidv4();

		$dataAsset				= $assetModel->getAll(['assetId' => $assetId]);
		$dataTagging 			= $assetTaggingModel->getAll(['assetId' => $assetId]);
		$dataParameter			= $parameterModel->getAll(['assetId' => $assetId, 'deletedAt' => null]);
		$dataAssetTag			= $assetTagModel->getAll(['assetId' => $assetId]);
		$dataAssetTagLocation	= $assetTagLocationModel->getAll(['assetId' => $assetId]);

		if (!checkLimitAsset()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your assets has reached the limit"]);
		}

		try {
			$photoAsset = "";
			if (!empty($dataAsset)) {
				foreach ($dataAsset as $key => $value) {
					if ($value['photo'] != "" && $value['photo'] != null && $value['photo'] != "null") {

						$str = str_replace(base_url(), "", $value['photo']);
						$img1 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir')  . $str);
						$encode = base64_encode($img1);
						$decode = base64_decode($encode);

						$size = getimagesizefromstring($decode);
						if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
							die('Base64 value is not a valid image');
						}
						$ext = substr($size['mime'], 6);
						if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
							die('Unsupported image type');
						}
						$dirPath = "upload/Asset/file" . $adminId;
						$filename = "IMG_" . $this->randomString() . '.' . "{$ext}";
						$img_file = $_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . "/" . $filename;
						file_put_contents($img_file, $decode);
						$photoAsset = base_url() . '/' . $dirPath . '/' . $filename;
					}
				}
			}

			if (count($dataParameter)) {

				foreach ($dataParameter as $key => $value) {
					$dataParameter[$key]['parameterId'] = uuidv4();
					$dataParameter[$key]['assetId'] = $newAssetId;
					$photo1 = "";
					$photo2 = "";
					$photo3 = "";
					if ($value['photo1'] != "" && $value['photo1'] != null && $value['photo1'] != "null" && $value['photo2'] != "" && $value['photo2'] != null && $value['photo2'] != "null" && $value['photo3'] != "" && $value['photo3'] != null && $value['photo3'] != "null") {

						//PHOTO 1
						$str1 = str_replace(base_url(), "", $value['photo1']);
						$img1 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $str1);
						$encode1 = base64_encode($img1);
						$decode1 = base64_decode($encode1);

						$size1 = getimagesizefromstring($decode1);
						if (empty($size1['mime']) || strpos($size1['mime'], 'image/') !== 0) {
							die('Base64 value is not a valid image');
						}
						$ext1 = substr($size1['mime'], 6);
						if (!in_array($ext1, ['png', 'gif', 'jpeg'])) {
							die('Unsupported image type');
						}

						$dirPath = "upload/Asset/file" . $adminId;
						$filename1 = "IMG_PARAM_H_" . $this->randomString() . '.' . "{$ext1}";
						$img_file1 = $_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . "/" . $filename1;
						file_put_contents($img_file1, $decode1);
						$photo1 = base_url() . '/' . $dirPath . '/' . $filename1;

						//PHOTO 2
						$str2 = str_replace(base_url(), "", $value['photo2']);
						$img2 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $str2);
						$encode2 = base64_encode($img2);
						$decode2 = base64_decode($encode2);

						$size2 = getimagesizefromstring($decode2);
						if (empty($size2['mime']) || strpos($size2['mime'], 'image/') !== 0) {
							die('Base64 value is not a valid image');
						}
						$ext2 = substr($size2['mime'], 6);
						if (!in_array($ext2, ['png', 'gif', 'jpeg'])) {
							die('Unsupported image type');
						}

						$filename2 = "IMG_PARAM_M_" . $this->randomString() . '.' . "{$ext2}";
						$img_file2 = $_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . "/" . $filename2;
						file_put_contents($img_file2, $decode2);
						$photo2 = base_url() . '/' . $dirPath . '/' . $filename2;

						//PHOTO 3
						$str3 = str_replace(base_url(), "", $value['photo3']);
						$img3 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $str3);
						$encode3 = base64_encode($img3);
						$decode3 = base64_decode($encode3);

						$size3 = getimagesizefromstring($decode3);
						if (empty($size3['mime']) || strpos($size3['mime'], 'image/') !== 0) {
							die('Base64 value is not a valid image');
						}
						$ext3 = substr($size['mime'], 6);
						if (!in_array($ext3, ['png', 'gif', 'jpeg'])) {
							die('Unsupported image type');
						}

						$filename3 = "IMG_PARAM_L_" . $this->randomString() . '.' . "{$ext3}";
						$img_file3 = $_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . "/" . $filename3;
						file_put_contents($img_file3, $decode3);
						$photo3 = base_url() . '/' . $dirPath . '/' . $filename3;

						$dataParameter[$key]['photo1'] = $photo1 != "" ? $photo1 : null;
						$dataParameter[$key]['photo2'] = $photo2 != "" ? $photo2 : null;
						$dataParameter[$key]['photo3'] = $photo3 != "" ? $photo3 : null;
					}
				}
			}
			if (count($dataTagging)) {
				for ($a = 0; $a < count($dataTagging); $a++) {
					$dataTagging[$a]['assetTaggingId'] = uuidv4();
					$dataTagging[$a]['assetId'] = $newAssetId;
					$dataTagging[$a]['userId'] = $adminId;
				}
			}
			if (count($dataAssetTag)) {
				for ($b = 0; $b < count($dataAssetTag); $b++) {
					$dataAssetTag[$b]['assetTagId'] = uuidv4();
					$dataAssetTag[$b]['assetId'] = $newAssetId;
				}
			}
			if (count($dataAssetTagLocation)) {
				for ($b = 0; $b < count($dataAssetTagLocation); $b++) {
					$dataAssetTagLocation[$b]['assetTagLocationId'] = uuidv4();
					$dataAssetTagLocation[$b]['assetId'] = $newAssetId;
				}
			}

			$asset = [
				'assetId'		=> $newAssetId,
				'userId'		=> $adminId,
				'assetStatusId'	=> $dataAsset[0]['assetStatusId'],
				'assetName'		=> $dataAsset[0]['assetName'] . ' Copy',
				'photo'			=> $photoAsset != "" ? $photoAsset : null,
				'description'	=> $dataAsset[0]['description'],
				'schManual'		=> $dataAsset[0]['schManual'],
				'schType'		=> $dataAsset[0]['schType'],
				'schFrequency'	=> $dataAsset[0]['schFrequency'],
				'schWeeks'		=> $dataAsset[0]['schWeeks'],
				'schWeekDays'	=> $dataAsset[0]['schWeekDays'],
				'schDays'		=> $dataAsset[0]['schDays']
			];

			if (strpos($dataAsset[0]['assetNumber'], '_') !== false) {
				$asset['assetNumber'] = explode("_", $dataAsset[0]['assetNumber'])[0] . '_' . $this->randomString();
			} else {
				$asset['assetNumber'] = explode("_", $dataAsset[0]['assetNumber'])[0] . '_' . $this->randomString();
			}



			$assetModel->insert($asset);
			$parameterModel->insertBatch($dataParameter);
			$assetTaggingModel->insertBatch($dataTagging);
			$assetTagModel->insertBatch($dataAssetTag);
			$assetTagLocationModel->insertBatch($dataAssetTagLocation);

			$dt = $assetModel->getAll(['userId' => $adminId, 'assetId' => $newAssetId]);
			if ($this->isJson($dt[0]['description'])) {
				$dt[0]['description'] = json_decode($dt[0]['description']);
			}
			sendLog('Duplicate asset', $newAssetId, json_encode($dt));
			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success duplicate data',
				'data' => $dt
			));
		} catch (Exception $e) {
			return $this->response->setJSON(array(
				'status' => $e->getCode(),
				'message' => $e->getMessage(),
				'data' => []
			));
		}
	}

	public function exportPerAsset($assetId)
	{
		$spreadsheet = new Spreadsheet();
		$assetModel = new AssetModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();

		$adminId = $this->session->get('adminId');
		$dataAsset = $assetModel->getAll(['userId' => $adminId, 'assetId' => $assetId, 'deletedAt' => null]);
		$dataParameter = $parameterModel->getAll(['assetId' => $assetId, 'deletedAt' => null]);
		$dataTagging = $assetTaggingModel->getAll(['assetId' => $assetId]);
		$rfid = "";
		$coordinat = "";

		// sheet 1
		$sheet = $spreadsheet->setActiveSheetIndex(0);
		$sheet->setTitle('Asset');

		$header1 = [
			"Asset Name",
			"Asset Number",
			"Details",
			"Location",
			"Tag",
			"Schedule",
			"Schedule Type",
			"Schedule Daily",
			"Schedule Week Days",
			"Schedule Month Days",
			"Schedule Month Week On",
			"Schedule Month Week Days",
			"RFID",
			"Coordinate",
			"Operation Status"
		];

		$sheet->mergeCells('A1:A2');
		$sheet->mergeCells('B1:B2');
		$sheet->mergeCells('C1:C2');
		$sheet->mergeCells('D1:D2');
		$sheet->mergeCells('E1:E2');
		$sheet->mergeCells('F1:F2');
		$sheet->mergeCells('G1:G2');
		$sheet->mergeCells('H1:H2');
		$sheet->mergeCells('I1:I2');
		$sheet->mergeCells('J1:J2');
		$sheet->mergeCells('K1:K2');
		$sheet->mergeCells('L1:L2');
		$sheet->mergeCells('M1:M2');
		$sheet->mergeCells('N1:N2');
		$sheet->mergeCells('O1:O2');
		$styleArray = [
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];

		$s1 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FFFFFF00',
				]
			],
		];
		$s2 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FF669933',
				]
			],
		];
		$s3 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FFFFCC00',
				]
			],
		];
		$s4 = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FF999999',
				]
			],
		];
		$styleArray2 = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			]
		];
		$spreadsheet->getActiveSheet()->getStyle('A1:O2')->applyFromArray($styleArray);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($s1);
		$spreadsheet->getActiveSheet()->getStyle('D1:E1')->applyFromArray($s2);
		$spreadsheet->getActiveSheet()->getStyle('F1:L1')->applyFromArray($s1);
		$spreadsheet->getActiveSheet()->getStyle('M1:N1')->applyFromArray($s3);
		$spreadsheet->getActiveSheet()->getStyle('O1:O1')->applyFromArray($s4);
		$spreadsheet->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleArray2);
		$sheet->fromArray($header1, NULL, 'A1');

		if (!empty($dataTagging)) {
			foreach ($dataTagging as $key => $value) {
				if ($value['assetTaggingtype'] == 'rfid') {
					$rfid = $value['assetTaggingValue'];
				}
				if ($value['assetTaggingtype'] == 'coordinat') {
					$coordinat = $value['assetTaggingValue'];
				}
			}
		}

		$column = 3;
		if (count($dataAsset)) {
			foreach ($dataAsset as $key => $value) {
				$spreadsheet->setActiveSheetIndex(0)
					->setCellValue('A' . $column, $value['assetName'])
					->setCellValue('B' . $column, $value['assetNumber'])
					->setCellValue('C' . $column, $this->isJson($value['description']) ? "" : $value['description'])
					->setCellValue('D' . $column, $value['tagLocationName'])
					->setCellValue('E' . $column, $value['tagName'])
					->setCellValue('F' . $column, $value['schManual'] == 1 ? "Manual" : "Automatic")
					->setCellValue('G' . $column, $value['schType'])
					->setCellValue('H' . $column, $value['schFrequency'])
					->setCellValue('I' . $column, $value['schType'] == 'Weekly' ? $value['schWeekDays'] : "")
					->setCellValue('J' . $column, $value['schDays'])
					->setCellValue('K' . $column, $value['schWeeks'])
					->setCellValue('L' . $column, $value['schType'] == 'Monthly' ? $value['schWeekDays'] : "")
					->setCellValue('M' . $column, $rfid)
					->setCellValue('N' . $column, $coordinat)
					->setCellValue('O' . $column, $value['assetStatusName']);
				$column++;
			}
		}

		// sheet 2
		$spreadsheet->createSheet();
		$sheet2 = $spreadsheet->setActiveSheetIndex(1);
		$spreadsheet->getActiveSheet()->setTitle('Description');

		$sheet2->setCellValue('A1', 'Asset Number');
		$sheet2->setCellValue('B1', 'Description');
		$sheet2->setCellValue('B2', 'key');
		$sheet2->setCellValue('C2', 'value');
		$sheet2->mergeCells('A1:A2');
		$sheet2->mergeCells('B1:C1');
		$styleSheet2 = [
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FFFFFF00',
				]
			],
		];
		$ss2 = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];
		$spreadsheet->getActiveSheet(1)->getStyle('A1:C2')->applyFromArray($styleSheet2);

		if (!empty($dataAsset)) {
			if ($this->isJson($dataAsset[0]['description'])) {
				$description = json_decode($dataAsset[0]['description']);
				$col = 3;
				foreach ($description as $key => $value) {
					$sheet2->setCellValue('A' . $col, $dataAsset[0]['assetNumber']);
					$sheet2->setCellValue('B' . $col, $value->key);
					$sheet2->setCellValue('C' . $col, $value->value);
					$col++;
				}
				$spreadsheet->getActiveSheet(1)->getStyle('A3:' . 'C' . (count($description) + 2))->applyFromArray($ss2);
			}
		}

		// sheet 3
		$spreadsheet->createSheet();
		$sheet3 = $spreadsheet->setActiveSheetIndex(2);
		$spreadsheet->getActiveSheet()->setTitle('Parameter');

		$header3 = [
			"#",
			"Parameter",
			"Description",
			"Max",
			"Min",
			"Normal",
			"Abnormal",
			"Uom",
			"Option",
			"Input Type",
			"Show On"
		];

		$styleSheet3 = [
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FFFFFF00',
				]
			],
		];

		$ss3 = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];
		$sheet3->fromArray($header3, NULL, 'A1');
		$sheet3->getStyle('A1:K1')->applyFromArray($styleSheet3);

		if (!empty($dataParameter)) {
			$i = 2;
			foreach ($dataParameter as $key => $value) {
				$sheet3->setCellValue('A' . $i, $key + 1);
				$sheet3->setCellValue('B' . $i, $value['parameterName']);
				$sheet3->setCellValue('C' . $i, $value['description']);
				$sheet3->setCellValue('D' . $i, $value['max']);
				$sheet3->setCellValue('E' . $i, $value['min']);
				$sheet3->setCellValue('F' . $i, $value['normal']);
				$sheet3->setCellValue('G' . $i, $value['abnormal']);
				$sheet3->setCellValue('H' . $i, $value['uom']);
				$sheet3->setCellValue('I' . $i, $value['option']);
				$sheet3->setCellValue('J' . $i, $value['inputType']);
				$sheet3->setCellValue('K' . $i, $value['showOn']);
				$i++;
			}
			$spreadsheet->getActiveSheet(1)->getStyle('A2:' . 'K' . (count($dataParameter) + 1))->applyFromArray($ss3);
		}

		$filename = $dataAsset[0]['assetName'] . date("d M Y") . '.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '');
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
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

	private function randomString()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		$n = 5;
		for ($i = 0; $i < $n; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}

		return $randomString;
	}
}
