<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\ApplicationSettingModel;
use App\Models\AssetStatusModel;

class Application extends BaseController
{
    public function index()
    {
        $appSettingModel = new ApplicationSettingModel();
        $assetStatusModel = new AssetStatusModel();
        $appSetting = $appSettingModel->findAll();
        $assetStatus = $assetStatusModel->getWhere('deletedAt', null)->getResultArray();
        $data = array(
            'title' => 'Setting Application',
            'subtitle' => 'Setting Application'
        );
        $data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Setting Application",
				"link"	=> "Application"
			],
		];
        $data['appSetting'] = $appSetting;
        $data['assetStatus'] = $assetStatus;
        return $this->template->render('Setting/Application/index.php', $data);
    }

    public function saveSetting()
    {
        $appSettingModel = new ApplicationSettingModel();
        $post = $this->request->getPost();
        $file = $this->request->getFile('appLogo');
        $appSettingId = $post['appSettingId'];
        $appSetting = $appSettingModel->where('appSettingId', $appSettingId)->get()->getResultArray();
        if (count($appSetting) > 0) {
            if ($file != null) {
                $name = 'LOGO_' . $file->getRandomName();
                $logoExist = $appSetting[0]['appLogo'];
                unlink('../public/assets/uploads/img/' . $logoExist);
                $file->move('../public/assets/uploads/img', $name);
                $data = array(
                    'userId' => $post['userId'],
                    'appName' => $post['appName'],
                    'appLogo' => $name,
                );
                $appSettingModel->update($appSettingId, $data);
                echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
            }else{
                $data = array(
                    'userId' => $post['userId'],
                    'appName' => $post['appName'],
                );
                $appSettingModel->update($appSettingId, $data);
                echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
            }
        }else{
            if ($post['appSettingId'] != '') {
                $name = 'LOGO_' . $file->getRandomName();
                $file->move('../public/assets/uploads/img', $name);
                $data = array(
                    'appSettingId' => $post['appSettingId'],
                    'userId' => $post['userId'],
                    'appName' => $post['appName'],
                    'appLogo' => $name,
                );
                $appSettingModel->insert($data);
                echo json_encode(array('status' => 'success', 'message' => 'You have successfully save data.', 'data' => $data));
            }else{
                echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $post));
            }
        }
        die();
    }

    public function saveStatus()
    {
        $assetStatusModel = new AssetStatusModel();
        $post = $this->request->getPost();
        $lengthStatusName = count($post['statusName']);
        for ($i=0; $i < $lengthStatusName; $i++) { 
            $data = array(
                'assetStatusId' => null,
                'userId' => $post['userId'],
                'assetStatusName' => $post['statusName'][$i],
            );
            $assetStatusModel->insert($data);
        }
        echo json_encode(array('status' => 'success', 'message' => '', 'data' => $post));
        die();
    }

    public function deleteStatus()
    {
        $assetStatusModel = new AssetStatusModel();
        $json = $this->request->getJSON();
        if ($json != '') {
            $id = $json->assetStatusId;
            $assetStatusModel->deleteById($id);
            echo json_encode(array('status' => 'success', 'message' => '', 'data' => $id));
        }else{
            echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $json));
        }
        die();
    }

    public function updateStatus()
    {
        $assetStatusModel = new AssetStatusModel();
        $json = $this->request->getJSON();
        if ($json != '') {
            $id = $json->assetStatusId;
            $data = array(
                'assetStatusName' => $json->assetStatusName,
            );

            $assetStatusModel->update($id, $data);
        }
        die();
    }
}
