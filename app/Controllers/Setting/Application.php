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
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        }

        // $userIdApp = $_SESSION["userIdApp"] ?? "fcc9766a-9bda-4fd3-a755-a24130d2f58c";
        $userIdApp = $_SESSION["userIdApp"] ?? "";
        if($userIdApp == ""){
            return View('errors/customError', ['ErrorCode'=>400,'ErrorMessage'=>"Sorry, You don't have any registered appication, please Register New Logsheet App or Relogin First"]);
        }

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
        $userId = $this->request->getVar('userId') ?? "65910438-b82d-4414-95cc-b3165527e08f";
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

            if($appLogoLight != ""){
                $appLogoLight = str_replace('data:image/png;base64,', '', $appLogoLight);
                $appLogoLight = str_replace(' ', '+', $appLogoLight);
                $dtAppLL = base64_decode($appLogoLight);

                $fileName = "AppLogoL_" . uniqid() . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . $fileName, $dtAppLL);
                $data["appLogoLight"] = base_url() . "/" . $dirPath . $fileName;
            }

            if($appLogoDark != ""){
                $appLogoDark = str_replace('data:image/png;base64,', '', $appLogoDark);
                $appLogoDark = str_replace(' ', '+', $appLogoDark);
                $dtAppLD = base64_decode($appLogoDark);

                $fileName = "AppLogoD_" . uniqid() . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . $fileName, $dtAppLD);
                $data["appLogoDark"] = base_url() . "/" . $dirPath . $fileName;
            }

            if($appLogoIcon != ""){
                $appLogoIcon = str_replace('data:image/png;base64,', '', $appLogoIcon);
                $appLogoIcon = str_replace(' ', '+', $appLogoIcon);
                $dtAppLI = base64_decode($appLogoIcon);

                $fileName = "AppLogoI_" . uniqid() . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . $dirPath . $fileName, $dtAppLI);
                $data["appLogoIcon"] = base_url() . "/" . $dirPath . $fileName;
            }

            if (!empty($appSetting)) {
                $appSettingModel->update($appSettingId, $data);
            } else {
                $data["appSettingId"] = null;

                $appSettingModel->insert($data);
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
                        "userId" => $_SESSION["userId"] ?? "65910438-b82d-4414-95cc-b3165527e08f",
                        "assetStatusName" => $val["assetStatusName"]
                    ));
                } else {
                    array_push($dataUpdate, array(
                        "assetStatusId" => $val["assetStatusId"],
                        // "userId" => $_SESSION["userId"] ?? "65910438-b82d-4414-95cc-b3165527e08f",
                        "assetStatusName" => $val["assetStatusName"]
                    ));
                }
            }
        }

        try {
            if (!empty($dataInsert)) {
                $assetStatusModel->insertBatch($dataInsert);
            }
            if (!empty($dataUpdate)) {
                $assetStatusModel->updateBatch($dataUpdate, "assetStatusId");
            }
            if (!empty($deletedId)) {
                $assetStatusModel->whereIn("assetStatusId", $deletedId)->delete();
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
