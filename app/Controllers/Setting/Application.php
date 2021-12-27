<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\ApplicationSettingModel;
use App\Models\AssetStatusModel;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;
use Exception;

class Application extends BaseController
{
    public function index()
    {
        if(!checkRoleList("APPLICATION.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

        $userIdApp = $this->session->get("adminId");
        $appSettingModel = new ApplicationSettingModel();
        $assetStatusModel = new AssetStatusModel();
        $appSetting = $appSettingModel->where("userId", $userIdApp)->get()->getRowArray();
        $assetStatus = $assetStatusModel->orderBy('createdAt', 'asc')->getWhere('deletedAt', null)->getResultArray();
        $data = array(
            'title' => 'Setting Application',
            'subtitle' => 'Setting Application'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Setting Application",
                "link"    => "Application"
            ],
        ];
        $data['appSetting'] = $appSetting;
        $data['assetStatus'] = $assetStatus;
        return $this->template->render('Setting/Application/index.php', $data);
    }

    public function saveSetting()
    {
        if(!checkRoleList("APPLICATION.MODIFY")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $appSettingModel = new ApplicationSettingModel();

        $appSettingId = $this->request->getVar('appSettingId') ?? "";
        $userId = $this->session->get("adminId");
        $appName = $this->request->getVar('appName');
        $appLogoLight = $this->request->getVar('appLogoLight');
        $appLogoDark = $this->request->getVar('appLogoDark');
        $appLogoIcon = $this->request->getVar('appLogoIcon');

        $appSetting = $appSettingModel->where(['appSettingId' => $appSettingId, "userId" => $userId])->get()->getRowArray();

        $dirPath = 'upload/applogo/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, TRUE);
        }

        try {
            $data["userId"] = $userId;
            $data["appName"] = $appName;
            
            $this->response->setCookie('appName', $data["appName"], 60 * 60 * 24 * 365);

            if($appLogoLight != ""){
                $appLogoLight = str_replace('data:image/png;base64,', '', $appLogoLight);
                $appLogoLight = str_replace(' ', '+', $appLogoLight);
                $dtAppLL = base64_decode($appLogoLight);

                $fileName = "AppLogoL_" . uniqid() . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . $fileName, $dtAppLL);
                $data["appLogoLight"] = base_url() . "/" . $dirPath . $fileName;

                $this->response->setCookie('appLogoLight', $data["appLogoLight"], 60 * 60 * 24 * 365);
            }

            if($appLogoDark != ""){
                $appLogoDark = str_replace('data:image/png;base64,', '', $appLogoDark);
                $appLogoDark = str_replace(' ', '+', $appLogoDark);
                $dtAppLD = base64_decode($appLogoDark);

                $fileName = "AppLogoD_" . uniqid() . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . $fileName, $dtAppLD);
                $data["appLogoDark"] = base_url() . "/" . $dirPath . $fileName;

                $this->response->setCookie('appLogoDark', $data["appLogoDark"], 60 * 60 * 24 * 365);
            }

            if($appLogoIcon != ""){
                $appLogoIcon = str_replace('data:image/png;base64,', '', $appLogoIcon);
                $appLogoIcon = str_replace(' ', '+', $appLogoIcon);
                $dtAppLI = base64_decode($appLogoIcon);

                $fileName = "AppLogoI_" . uniqid() . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . $fileName, $dtAppLI);
                $data["appLogoIcon"] = base_url() . "/" . $dirPath . $fileName;
                
                $this->response->setCookie('appLogoIcon', $data["appLogoIcon"], 60 * 60 * 24 * 365);
            }

            if (!empty($appSetting)) {
                $appSettingModel->update($appSettingId, $data);
                $activity = 'Update setting application';
                sendLog($activity, null, json_encode($data));
            } else {
                $data["appSettingId"] = null;
                $appSettingModel->insert($data);

                $activity = 'Add setting application';
                sendLog($activity, null, json_encode($data));
                
            }

            return $this->response->setJSON([
                'status' => 200,
                'message' => "You have successfully save data.",
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function saveAssetStatus()
    {
        if(!checkRoleList("APPLICATION.ASSETSTATUS.MODIFY")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $assetModel = new AssetModel();
        $scheduleTrxModel = new ScheduleTrxModel();
        // $parameterModel = new ParameterModel();
        $assetStatusModel = new AssetStatusModel();
        $json = $this->request->getJSON(true);

        $deletedId = [];
        $dataInsert = [];
        $dataUpdate = [];
        $messageDeleted = "";
        foreach ($json as $val) {
            if ($val['deleted'] == true) {
                $cekAsset = $assetModel->getAll(["assetStatusId" => $val["assetStatusId"]]);
                $cekSchTrx = $scheduleTrxModel->getAll(["assetStatusId" => $val["assetStatusId"]]);
                // $cekParam = $parameterModel->getAll(["assetStatusId" => $val["assetStatusId"]]);
                if (empty($cekAsset) & empty($cekSchTrx)) {
                    array_push($deletedId, $val["assetStatusId"]);
                } else {
                    $messageDeleted = "Some Asset Status is Already Used";
                }
            } else {
                if ($val["isNew"] == true) {
                    array_push($dataInsert, array(
                        "assetStatusId" => null,
                        "userId" => $this->session->get("adminId"),
                        "assetStatusName" => $val["assetStatusName"]
                    ));
                } else {
                    array_push($dataUpdate, array(
                        "assetStatusId" => $val["assetStatusId"],
                        // "userId" => $this->session->get("adminId"),
                        "assetStatusName" => $val["assetStatusName"]
                    ));
                }
            }
        }

        try {
            if (!empty($dataInsert)) {
                $assetStatusModel->insertBatch($dataInsert);

                $activity = 'Add asset status';
                sendLog($activity, null, json_encode($dataInsert));
            }
            if (!empty($dataUpdate)) {
                $assetStatusModel->updateBatch($dataUpdate, "assetStatusId");

                $activity = 'Update asset status';
                sendLog($activity, null, json_encode($dataInsert));
            }
            if (!empty($deletedId)) {
                $assetStatusModel->whereIn("assetStatusId", $deletedId)->delete();

                $activity = 'Delete asset status';
                sendLog($activity, null, json_encode($dataInsert));
            }

            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success Update Asset Status Name." . ($messageDeleted != "" ? "But, " . $messageDeleted : ""),
                'data' => []
            ], 200);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function deleteAssetStatus()
    {
        if(!checkRoleList("APPLICATION.ASSETSTATUS.DELETE")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }
        
        $assetModel = new AssetModel();
        $json = $this->request->getJSON();
        $id = $json->assetStatusId;
        $data = $assetModel->selectMin('createdAt')->where('assetStatusId', $id)->get()->getResultArray();
        if ($data[0]['createdAt'] != null) {
            echo json_encode(array('status' => 'exist', 'message' => 'This data already use since ', 'data' => $data[0]['createdAt']));
        } else {
            echo json_encode(array('status' => 'noexist', 'message' => '', 'data' => $json));
        }
        die();
    }
}
