<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\VersionAppsModel;

class VersionApps extends BaseController
{
	public function index()
	{
        if(!checkRoleList("VERSIONAPPS.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$data = array(
			'title' => 'Version Apps',
			'subtitle' => 'Versioning Mobile Application'
		);
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Version Apps",
				"link"	=> "VersionApps"
			],
		];
		return $this->template->render('Setting/VersionApps/index', $data);
	}

	public function datatable()
	{
		$request = \Config\Services::request();

        if(!checkRoleList("VERSIONAPPS.VIEW")){
			echo json_encode(array(
				"draw" => $request->getPost('draw'),
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => [],
				'status' => 403,
				'message' => "You don't have access to this page"
			));
        }

		$table = "tblt_versionApp";
		$column_order = array('versionAppId', 'name', 'version', 'description', 'createdAt');
		$column_search = array('versionAppId', 'name', 'version', 'description', 'createdAt');
		$order = array('createdAt' => 'asc');
		$DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
		$where = [];
		$list = $DTModel->datatable($where);
		$output = array(
			"draw" => $request->getPost('draw'),
			"recordsTotal" => $DTModel->count_all($where),
			"recordsFiltered" => $DTModel->count_filtered($where),
			"data" => $list,
			"status" => 200,
			"message" => "success"
		);
		echo json_encode($output);
	}

	public function new()
	{
        if(!checkRoleList("VERSIONAPPS.RELEASE")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
		}

		$model = new VersionAppsModel();
		$post = $this->request->getPost();
		$file = $this->request->getFile('fileApp');
		if ($post['name'] != '') {
			$file = $this->request->getFile('fileApp');
			$name = $post['name'] . '_' . 'v' . $post['version'] . '.apk';
			$file->move('../public/assets/uploads/apk', $name);
			$data = array(
				'versionAppId' => $post['versionAppId'],
				'userId' => $this->session->get("adminId"),
				'name' => $post['name'],
				'version' => $post['version'],
				'description' => $post['description'],
				'fileApp' => $name
			);
			$model->insert($data);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully add data.', 'data' => $data));
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $post, 'file' => $file));
		}
		die();
	}

	public function detail()
	{
        if(!checkRoleList("VERSIONAPPS.DETAIL")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
		}

		$versionAppsModel = new VersionAppsModel();
		$json = $this->request->getJSON();
		if ($json->versionAppId != '') {
			$versionAppId = $json->versionAppId;
			$data = $versionAppsModel->getById($versionAppId);
			echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $json));
		}
		die();
	}

	public function download($id)
	{
        if(!checkRoleList("VERSIONAPPS.DOWNLOAD")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$versionAppsModel = new VersionAppsModel();
		$appsData = $versionAppsModel->where('versionAppId', $id)->get()->getResultArray();
		$apk = $appsData[0]['fileApp'];
		return $this->response->download('../public/assets/uploads/apk/' . $apk, null);
	}

	public function edit()
	{
        if(!checkRoleList("VERSIONAPPS.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

		$versionAppsModel = new VersionAppsModel();
		$json = $this->request->getJSON();
		if ($json->versionAppId != '') {
			$versionAppId = $json->versionAppId;
			$data = $versionAppsModel->getById($versionAppId);
			echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $json));
		}
		die();
	}

	public function update()
	{
        if(!checkRoleList("VERSIONAPPS.UPDATE")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
		}

		$versionAppsModel = new VersionAppsModel();
		$post = $this->request->getPost();
		$versionAppId = $post['versionAppId'];
		if ($versionAppId != '') {
			$file = $this->request->getFile('fileApp');
			if ($file == null) {
				$dataVersionApps = $versionAppsModel->where('versionAppId', $versionAppId)->get()->getResultArray();
				$name = $post['name'] . '_' . 'v' . $post['version'] . '.apk';
				rename('../public/assets/uploads/apk/' . $dataVersionApps[0]['fileApp'], '../public/assets/uploads/apk/' . $name);
				$data = array(
					'userId' => $this->session->get("adminId"),
					'name' => $post['name'],
					'version' => $post['version'],
					'description' => $post['description'],
					'fileApp' => $name
				);
				$versionAppsModel->update($versionAppId, $data);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
			} else {
				$dataVersionApps = $versionAppsModel->where('versionAppId', $versionAppId)->get()->getResultArray();
				unlink('../public/assets/uploads/apk/' . $dataVersionApps[0]['fileApp']);
				$file = $this->request->getFile('fileApp');
				$name = $post['name'] . '_' . 'v' . $post['version'] . '.apk';
				$file->move('../public/assets/uploads/apk', $name);
				$data = array(
					'userId' => $this->session->get("adminId"),
					'name' => $post['name'],
					'version' => $post['version'],
					'description' => $post['description'],
					'fileApp' => $name,
				);
				$versionAppsModel->update($versionAppId, $data);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
			}
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $post));
		}
		die();
	}

	public function delete()
	{
        if(!checkRoleList("VERSIONAPPS.DELETE")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
		}

		$versionAppsModel = new VersionAppsModel();
		$json = $this->request->getJSON();
		$versionAppId = $json->versionAppId;
		if ($versionAppId != '') {
			$data = $versionAppsModel->where('versionAppId', $versionAppId)->get()->getResultArray();
			$lengthData = count($data);
			if ($lengthData > 0) {
				unlink('../public/assets/uploads/apk/' . $data[0]['fileApp']);
				$versionAppsModel->deleteById($versionAppId);
				echo json_encode(array('status' => 'success', 'message' => 'You have successfully deleted data.', 'data' => $json));
			}
		} else {
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $json));
		}
		die();
	}
}
