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
        if(!checkRoleList("MASTER.ASSET.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
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

        if(!checkRoleList("MASTER.ASSET.VIEW")){
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
        if(!checkRoleList("MASTER.ASSET.ADD.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
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
        if(!checkRoleList("MASTER.ASSET.ADD")){
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
        if(!checkRoleList("MASTER.ASSET.DETAIL")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
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
        if(!checkRoleList("MASTER.ASSET.UPDATE")){
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
						'userId' => '',
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
						'userId' => '',
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
        if(!checkRoleList("MASTER.ASSET.DELETE")){
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
        if(!checkRoleList("MASTER.ASSET.PARAMETER.IMPORT.SAMPLE")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        }

		return $this->response->download($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . 'download/sampleImportParameter.xlsx', null);
	}

	public function sortingParameter()
	{
        if(!checkRoleList("MASTER.ASSET.PARAMETER.SORT")){
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
}