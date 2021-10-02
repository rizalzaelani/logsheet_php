<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\VersionAppsModel;

class VersionApps extends BaseController
{
	public function index()
	{
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
		$table = "tblt_versionApp";
        $column_order = array('versionAppId', 'name', 'version', 'description', 'createdAt');
        $column_search = array('versionAppId', 'name', 'version', 'description', 'createdAt');
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
            "status" => 200,
            "message" => "success"
        );
        echo json_encode($output);
	}

	public function new()
	{
		$model = new VersionAppsModel();
		$post = $this->request->getPost();
		if ($post['name'] != '') {
			$data = array(
				'versionAppId' => $post['versionAppId'],
				'userId' => $post['userId'],
				'name' => $post['name'],
				'version' => $post['version'],
				'description' => $post['description'],
				'fileApp' => $post['fileApp']
			);
			$model->insert($data);
			echo json_encode(array('status' => 'success', 'message' => 'You have successfully add data.', 'data' => $post));
		}else{
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $post));
		}
		die();
	}

	public function detail()
	{
		$versionAppsModel = new VersionAppsModel();
		$json = $this->request->getJSON();
		if ($json->versionAppId != '') {
			$versionAppId = $json->versionAppId;
			$data = $versionAppsModel->getById($versionAppId);
			echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		}else{
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $data));
		}
		die();
	}

	public function edit()
	{
		$versionAppsModel = new VersionAppsModel();
		$json = $this->request->getJSON();
		if ($json->versionAppId != '') {
			$versionAppId = $json->versionAppId;
			$data = $versionAppsModel->getById($versionAppId);
			echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		}else{
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $data));
		}
		die();
	}

	public function update()
	{
		$versionAppsModel = new VersionAppsModel();
		$post = $this->request->getPost();
		$versionAppId = $post['versionAppId'];
		if ($versionAppId != '') {
			$data = array(
				'userId' => $post['userId'],
				'name' => $post['name'],
				'version' => $post['version'],
				'description' => $post['description'],
			);
			$versionAppsModel->update($versionAppId, $data);
			echo json_encode(array('status' => 'success', 'message' => '', 'data' => $data));
		}else{
			echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $data));
		}
	}

	public function delete()
	{
		$versionAppsModel = new VersionAppsModel();
        $json = $this->request->getJSON();
        $versionAppId = $json->versionAppId;
        if ($versionAppId != '') {
            $versionAppsModel->deleteById($versionAppId);
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully deleted data.', 'data' => $json));
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $json));
        }
        die();
	}
}
