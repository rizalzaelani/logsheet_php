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
use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

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
			return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
		}

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

		$filtTag = explode(",", $_POST["columns"][2]["search"]["value"] ?? '');
		$filtLoc = explode(",", $_POST["columns"][3]["search"]["value"] ?? '');
		$where = [
			'userId' => $this->session->get("adminId"),
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
		if (!checkRoleList("MASTER.ASSET.ADD")) {
			return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
		}

		$modelAsset = new AssetModel();

		$locationData = $this->db->table('tblm_tagLocation')->get()->getResult();
		$tagData = $this->db->table('tblm_tag')->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->where('deletedAt IS NULL')->get()->getResult();
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
		if (isset($post['assetId'])) {
			// asset
			$dataAsset = array(
				'assetId' => $assetId,
				'userId' => $this->session->get("adminId"),
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
					} else {
						$dataParam = array(
							'parameterId' => json_decode($post['parameter'][$i])->parameterId,
							'assetId' => $assetId,
							'sortId' => $i + 1,
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
		if (!checkRoleList("MASTER.ASSET.DETAIL")) {
			return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
		}

		$model = new AssetModel();
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

		$tagging = $assetTaggingModel->where('assetId', $assetId)->orderBy('assetTaggingtype', 'asc')->findAll();
		$tagData = $this->db->table('tblm_tag')->get()->getResult();
		$statusData = $this->db->table('tblm_assetStatus')->where('deletedAt IS NULL')->get()->getResult();
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
		if (!checkRoleList("MASTER.ASSET.UPDATE")) {
			return $this->response->setJSON([
				'status' => 403,
				'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
		}

		$assetModel = new AssetModel();
		$tagModel = new TagModel();
		$tagLocationModel = new TagLocationModel();
		$assetTagLocationModel = new AssetTagLocationModel();
		$assetTagModel = new AssetTagModel();
		$assetStatusModel = new AssetStatusModel();
		$assetTaggingModel = new AssetTaggingModel();
		$parameterModel = new ParameterModel();

		$post = $this->request->getPost();
		$assetId = $post['assetId'];
		$tag = $post['tag'];

		if (isset($post['assetId'])) {
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
					// var_dump($dataNewTag);
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

			// asset
			$dataAsset = array(
				'assetId' => $post['assetId'],
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
			if ($post['editedParameter'][0] != "") {
				$lengthEditedParameter = count($post['editedParameter']);
				for ($i = 0; $i < $lengthEditedParameter; $i++) {
					$dataEdited = json_decode($post['editedParameter'][$i]);
					$data = array(
						'parameterId'		=> $dataEdited->parameterId,
						'sortId'			=> $dataEdited->sortId,
						'parameterName'		=> $dataEdited->parameterName,
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
				}
			}

			// insert parameter
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

	public function download()
	{
		if (!checkRoleList("MASTER.ASSET.PARAMETER.IMPORT.SAMPLE")) {
			return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
		}

		return $this->response->download($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . 'download/sampleImportParameter.xlsx', null);
	}

	public function uploadFile()
	{
		$file = $this->request->getFile('importParam');
		if ($file) {
			$newName = "doc" . time() . '.xlsx';
			$file->move('../uploads/', $newName);
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('../uploads/' . $newName);
			$dataImport = [];
			foreach ($reader->getSheetIterator() as $sheet) {
				$numrow = 1;
				foreach ($sheet->getRowIterator() as $row) {
					if ($numrow > 1) {
						if ($row->getCellAtIndex(1) != '' && $row->getCellAtIndex(2) != '') {
							$dataImport[] = array(
								'no' => $row->getCellAtIndex(0)->getValue(),
								'parameterName' => $row->getCellAtIndex(1)->getValue(),
								'description' => $row->getCellAtIndex(2)->getValue(),

								'maxNormal' => (($row->getCellAtIndex(3)->getValue()) ? $row->getCellAtIndex(3)->getValue() : $row->getCellAtIndex(5)->getValue()),
								'minAbnormal' => (($row->getCellAtIndex(4)->getValue()) ? $row->getCellAtIndex(4)->getValue() : $row->getCellAtIndex(6)->getValue()),
								'uomOption' => (($row->getCellAtIndex(7)->getValue()) ? $row->getCellAtIndex(7)->getValue() : $row->getCellAtIndex(8)->getValue()),

								'max' => $row->getCellAtIndex(3)->getValue() ? $row->getCellAtIndex(3)->getValue() : null,
								'min' => $row->getCellAtIndex(4)->getValue() ? $row->getCellAtIndex(4)->getValue() : null,
								'normal' => $row->getCellAtIndex(5)->getValue() ? $row->getCellAtIndex(5)->getValue() : "",
								'abnormal' => $row->getCellAtIndex(6)->getValue() ? $row->getCellAtIndex(6)->getValue() : "",
								'option' => $row->getCellAtIndex(8)->getValue() ? $row->getCellAtIndex(8)->getValue() : "",
								'uom' => $row->getCellAtIndex(7)->getValue() ? $row->getCellAtIndex(7)->getValue() : "",

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
			if ($dataImport) {
				unlink('../uploads/' . $newName);
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
			return $this->response->setJson([
				'status' => 200,
				'message' => "Successfully Updated Data",
				'data' => []
			], 200);
		} catch (Exception $e) {
			throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function downloadSampleAsset()
	{
		if (!checkRoleList("MASTER.ASSET.PARAMETER.IMPORT.SAMPLE")) {
			return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
		}
		return $this->response->download($_SERVER['DOCUMENT_ROOT'] . '/download/SampleImportAsset.xlsx', null);
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
			$file->move('../uploads/', $name);
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('../uploads/' . $name);
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
					// foreach ($sheet->getRowIterator() as $row) {
					// 	$assetNumber = "";
					// 	$descSpecial = [];
					// 	if ($rowDescription > 2) {
					// 		$assetNumber = $row->getCellAtIndex(0)->getValue();
					// 		if (strtolower($assetNumber) == 'all') {
					// 			$descAll = [
					// 				'key' => $row->getCellAtIndex(1)->getValue(),
					// 				'value' => $row->getCellAtIndex(2)->getValue()
					// 			];
					// 			array_push($description, $descAll);
					// 			foreach ($dataAsset as $key => $value) {
					// 				if ($value['assetNumber'] != $assetNumber) {
					// 					$dataAsset[$key]['description'] = $description;
					// 				}
					// 			}
					// 		} else {
					// 			$cekAssetNumber = array_filter($dataAsset, function ($val) use ($assetNumber) {
					// 				return $val['assetNumber'] == $assetNumber;
					// 			});
					// 			if (!empty($cekAssetNumber)) {
					// 				$descSpecial['assetNumber'] = $assetNumber;
					// 				$descSpecial[] = [
					// 					'key' => $row->getCellAtIndex(1)->getValue(),
					// 					'value' => $row->getCellAtIndex(2)->getValue()
					// 				];
					// 				array_push($special, $descSpecial);
					// 			}
					// 		}
					// 	}
					// 	$rowDescription++;
					// }

					// for ($i = 0; $i < count($special); $i++) {
					// 	foreach ($dataAsset as $key => $val) {
					// 		$cekIsString = is_string($val['description']);
					// 		if (!$cekIsString) {
					// 			if ($special[$i]['assetNumber'] == $val['assetNumber']) {
					// 				array_push($dataAsset[$key]['description'], $special[$i][0]);
					// 			}
					// 		}
					// 	}
					// }
					// foreach ($dataAsset as $key => $value) {
					// 	$cekIsString = is_string($value['description']);
					// 	if (!$cekIsString) {
					// 		$dataAsset[$key]['description'] = json_encode($dataAsset[$key]['description']);
					// 	}
					// }
				}

				$rowParameter = 1;
				if ($sheet->getIndex() == 2) {
					foreach ($sheet->getRowIterator() as $row) {
						if ($rowParameter > 1) {
							$parameter[] = array(
								// 'parameterId' => $this->uuid(),
								'sortId' => $row->getCellAtIndex(0)->getValue(),
								'parameterName' => $row->getCellAtIndex(1)->getValue(),
								'description' => $row->getCellAtIndex(2)->getValue(),

								'maxNormal' => (($row->getCellAtIndex(3)->getValue()) ? $row->getCellAtIndex(3)->getValue() : $row->getCellAtIndex(5)->getValue()),
								'minAbnormal' => (($row->getCellAtIndex(4)->getValue()) ? $row->getCellAtIndex(4)->getValue() : $row->getCellAtIndex(6)->getValue()),

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
				// $rowDescription = 1;
				// $rowDesc = 2;
				// if ($sheet->getName() == 'Asset') {
				// 	foreach ($sheet->getRowIterator() as $index => $row) {
				// 		if ($rowDescription > 1) {
				// 			$desc = array(
				// 				$row->getCellAtIndex(15)->getValue(),
				// 				$row->getCellAtIndex(16)->getValue(),
				// 				$row->getCellAtIndex(17)->getValue(),
				// 				$row->getCellAtIndex(18)->getValue(),
				// 				$row->getCellAtIndex(19)->getValue(),
				// 			);
				// 			break;
				// 		}
				// 		$rowDescription++;
				// 	}
				// 	$descJson = [];
				// 	foreach ($sheet->getRowIterator() as $index => $row) {
				// 		if ($rowDesc > 3) {
				// 			$json['value'] = [
				// 				array('key' => $desc[0], 'value' => $row->getCellAtIndex(15)->getValue()),
				// 				array('key' => $desc[1], 'value' => $row->getCellAtIndex(16)->getValue()),
				// 				array('key' => $desc[2], 'value' => $row->getCellAtIndex(17)->getValue()),
				// 				array('key' => $desc[3], 'value' => $row->getCellAtIndex(18)->getValue()),
				// 				array('key' => $desc[4], 'value' => $row->getCellAtIndex(19)->getValue()),
				// 			];
				// 			array_push($descJson, $json);
				// 		}
				// 		$rowDesc++;
				// 	}
				// 	$lengthAsset = count($dataAsset);
				// 	for ($i = 0; $i < $lengthAsset; $i++) {
				// 		$descAsset = $dataAsset[$i]['description'];
				// 		if ($descAsset == "") {
				// 			$dataAsset[$i]['description'] = json_encode($descJson[$i]['value']);
				// 		}
				// 	}
				// }
				// $rowParam = 1;
				// for ($i = 0; $i < count($dataAsset); $i++) {
				// 	if ($sheet->getIndex() == $i + 1) {
				// 		$parameter = [];
				// 		// var_dump("test");
				// 		foreach ($sheet->getRowIterator() as $row) {
				// 			if ($rowParam > 1) {
				// 				$parameter[] = array(
				// 					'parameterId' => $this->uuid(),
				// 					'sortId' => $row->getCellAtIndex(0)->getValue(),
				// 					'parameterName' => $row->getCellAtIndex(1)->getValue(),
				// 					'description' => $row->getCellAtIndex(2)->getValue(),

				// 					'maxNormal' => (($row->getCellAtIndex(3)->getValue()) ? $row->getCellAtIndex(3)->getValue() : $row->getCellAtIndex(5)->getValue()),
				// 					'minAbnormal' => (($row->getCellAtIndex(4)->getValue()) ? $row->getCellAtIndex(4)->getValue() : $row->getCellAtIndex(6)->getValue()),

				// 					'max' => $row->getCellAtIndex(3)->getValue() ? $row->getCellAtIndex(3)->getValue() : null,
				// 					'min' => $row->getCellAtIndex(4)->getValue() ? $row->getCellAtIndex(4)->getValue() : null,
				// 					'normal' => $row->getCellAtIndex(5)->getValue() ? $row->getCellAtIndex(5)->getValue() : "",
				// 					'abnormal' => $row->getCellAtIndex(6)->getValue() ? $row->getCellAtIndex(6)->getValue() : "",
				// 					'option' => $row->getCellAtIndex(8)->getValue() ? $row->getCellAtIndex(8)->getValue() : "",
				// 					'uom' => $row->getCellAtIndex(7)->getValue() ? $row->getCellAtIndex(7)->getValue() : "",

				// 					'inputType' => $row->getCellAtIndex(9)->getValue(),
				// 					'showOn' => $row->getCellAtIndex(10)->getValue(),
				// 				);
				// 			}
				// 			$rowParam++;
				// 		}
				// 		$dataAsset[$i]['parameter'] = $parameter;
				// 	}
				// }
			}
			$reader->close();
			$data['dataAsset'] = $dataAsset;
			$data['parameter'] = $parameter;
			unlink('../uploads/' . $name);
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

	function uuid()
	{
		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff)
		);
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
		$userId = $this->session->get("adminId");
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
					'assetId' => $this->uuid(),
					'userId' => $userId,
					'assetName' => $dataAsset[$i]->assetName,
					'assetNumber' => $dataAsset[$i]->assetNumber,
					'description' => $dataAsset[$i]->description,
					'schManual' => $dataAsset[$i]->schManual == "Automatic" ? 0 : 1,
					'schType' => $dataAsset[$i]->schType,
					'schFrequency' => $dataAsset[$i]->schFrequency,
					'schWeeks' => $dataAsset[$i]->schType == 'Monthly' ? $dataAsset[$i]->schWeeks : '',
					'schDays' => $dataAsset[$i]->schType == 'Monthly' ? $dataAsset[$i]->schDays : '',
				);
				// schWeekDays
				$schWeekDays = explode(",", $dataAsset[$i]->schWeekDays);
				$arrWeekDays = [];
				foreach ($schWeekDays as $key => $val) {
					array_push($arrWeekDays, substr($val, 0, 2));
				}
				$strWeekDays = implode(",", $arrWeekDays);
				$dataInsert['schWeekDays'] = $strWeekDays;

				// asset status
				$status = $dataAsset[$i]->assetStatus;
				$dataStatus = $assetStatusModel->getByName($status);
				if ($dataStatus == NULL) {
					$newStatus = array(
						'assetStatusId' => $this->uuid(),
						'userId' => $userId,
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
					$dataParameter['parameterId'] = $this->uuid();
					$arrParameter['assetId'] = $dataInsert['assetId'];
					$parameterModel->insert($arrParameter);
				}
				// $parameter = $dataAsset[$i]->parameter;
				// foreach ($parameter as $key => $val) {
				// 	$parameter[$key]->assetId = $dataInsert['assetId'];
				// 	$parameterModel->insert($parameter[$key]);
				// }

				// tag
				$tag = $dataAsset[$i]->tag;
				// $arrTag = explode(",", $tag);
				foreach ($tag as $key => $val) {
					$dataTag = $tagModel->getByName($val);
					if ($dataTag == NULL) {
						$newTag = array(
							'tagId' => $this->uuid(),
							'userId' => $userId,
							'tagName' => $val,
							'description' => ''
						);
						$tagModel->insert($newTag);
						$insertNewAssetTag = array(
							'assetTagId' => $this->uuid(),
							'assetId' => $dataInsert['assetId'],
							'tagId' => $newTag['tagId']
						);
						$assetTagModel->insert($insertNewAssetTag);
					} else {
						$tagId = $dataTag['tagId'];
						$insertAssetTag = array(
							'assetTagId' => $this->uuid(),
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
							'tagLocationId' => $this->uuid(),
							'userId' => $userId,
							'tagLocationName' => $val,
							'latitude' => '',
							'longitude' => '',
							'description' => '',
						);
						$tagLocationModel->insert($newTagLocation);
						$insertNewAssetTagLocation = array(
							'assetTagLocationId' => $this->uuid(),
							'assetId' => $dataInsert['assetId'],
							'tagLocationId' => $newTagLocation['tagLocationId']
						);
						$assetTagLocationModel->insert($insertNewAssetTagLocation);
					} else {
						$tagLocationId = $dataTagLocation['tagLocationId'];
						$insertAssetTagLocation = array(
							'assetTagLocationId' => $this->uuid(),
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
						'assetTaggingId' => $this->uuid(),
						'assetId' => $dataInsert['assetId'],
						'assetTaggingValue' => $rfid,
						'assetTaggingtype' => 'rfid'
					);
					$assetTaggingModel->insert($dataRFID);
				}
				$coordinat = $dataAsset[$i]->coordinat;
				if ($coordinat != "") {
					$dataCoordinat = array(
						'assetTaggingId' => $this->uuid(),
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
}
