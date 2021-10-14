<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Controllers\Transaction\ScheduleTrx;
use App\Models\ApplicationSettingModel;
use App\Models\AssetStatusModel;
use App\Models\AssetModel;
use App\Models\ParameterModel;
use App\Models\ScheduleTrxModel;
use Exception;

class Application extends BaseController
{
    public function index()
    {
        $userIdApp = $_SESSION["userIdApp"] ?? "fcc9766a-9bda-4fd3-a755-a24130d2f58c";

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
        $appSettingModel = new ApplicationSettingModel();
        $file = $this->request->getFile('appLogo');
        $appSettingId = $this->request->getVar('appSettingId') ?? "";
        $userId = $this->request->getVar('userId') ?? "65910438-b82d-4414-95cc-b3165527e08f";
        $appName = $this->request->getVar('appName');

        $appSetting = $appSettingModel->where(['appSettingId' => $appSettingId, "userId" => $userId])->get()->getRowArray();

        $dirPath = 'upload/applogo/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, TRUE);
        }

        try {
            $data["userId"] = $userId;
            $data["appName"] = $appName;

            if (!empty($appSetting)) {
                if ($file != null) {
                    $name = 'LOGO_' . $file->getRandomName();
                    $fileTemp = str_replace(base_url() . "/", "", $appSetting['appLogo']);
                    unlink($fileTemp);
                    $file->move($dirPath, $name);

                    $data["appLogo"] = base_url() . "/" . $dirPath . "/" . $name;
                }

                $appSettingModel->update($appSetting["appSettingId"], $data);

                return $this->response->setJSON([
                    'status' => 200,
                    'message' => "You have successfully save data.",
                    'data' => $data
                ], 200);
            } else {
                if ($file == null) {
                    return $this->response->setJSON([
                        'status' => 400,
                        'message' => "Make Sure the Attachment Is Not Empty",
                        'data' => $data
                    ], 400);
                }
                
                $name = 'LOGO_' . $file->getRandomName();
                $file->move($dirPath, $name);

                $data["appSettingId"] = null;
                $data["appLogo"] = base_url() . "/" . $dirPath . "/" . $name;

                $appSettingModel->insert($data);
                return $this->response->setJSON([
                    'status' => 200,
                    'message' => "You have successfully save data.",
                    'data' => $data
                ], 200);
            }
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
        $assetStatusModel = new AssetStatusModel();
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
