<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;
use DateTime;
use Exception;

class Schedule extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Schedule',
            'subtitle' => 'Schedule'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Schedule",
                "link"    => "Schedule"
            ],
        ];

        $assetModel = new AssetModel();

        $data["assetManualData"] = $assetModel->getAll(["schManual" => "1", "deletedAt IS NULL" => null]);

        return $this->template->render('Setting/Schedule/index.php', $data);
    }

    public function getDataByMonth()
    {
        $month = $this->request->getVar('month') ?? date("m");
        $year = $this->request->getVar('year') ?? date("Y");

        $where = [
            "MONTH(scheduleFrom)" => $month,
            "YEAR(scheduleFrom)" => $year,
            "schManual" => '1'
        ];

        $schModel = new ScheduleTrxModel();
        $dataSch = $schModel->getAll($where);

        return $this->response->setJSON([
            'status' => 200,
            'message' => "success get data",
            'data' => $dataSch
        ], 200);
    }

    public function addScheduleAM()
    {
        $dataAssetAM = json_decode(($this->request->getVar("dataAssetAM") ?? "[]"), true);
        $start = $this->request->getVar("start");
        $end = $this->request->getVar("end");

        if (!validateDate($start, "Y-m-d") && !validateDate($end, "Y-m-d")) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => "Start and End is Not Valid Date",
                'alertType' => "warning",
                'data' => []
            ], 400);
        }

        $schModel = new ScheduleTrxModel();

        $getLastAssetId = $schModel->getAll(["assetId IN ('" . implode("','", array_column($dataAssetAM, "assetId")) . "')" => null, "scheduleTo >=" => $start], "scheduleTo", "desc");
        if (!empty($getLastAssetId)) {
            $assetName = implode(", ", array_unique(array_column($getLastAssetId, "assetName")));

            return $this->response->setJSON([
                'status' => 400,
                'message' => "Please Change Start of Date",
                "exception" => "Several Asset (" . $assetName . ") Cannot Start from <b>" . date("d F Y", strtotime($start)) . "</b>, You can Start From <b>" . date("d F Y", strtotime($getLastAssetId[0]["scheduleTo"] . " +1 days")) . "</b>",
                "alertType" => "warning",
                'data' => []
            ], 400);
        }

        $dataInsertSchAM = [];
        foreach ($dataAssetAM as $row) {
            $dtSAMTemp = array(
                "scheduleTrxId" => null,
                "assetId"       => $row["assetId"],
                "assetStatusId" => $row["assetStatusId"],
                "schManual"     => '1',
                "schType"       => 'None',
                "schFrequency"  => 1,
                "scheduleFrom"  => date("Y-m-d 00:00:00", strtotime($start)),
                "scheduleTo"    => date("Y-m-d 23:59:59", strtotime($end)),
                "condition"     => "Normal",
            );

            if ($row["adviceDate"]) {
                $dtSAMTemp["adviceDate"] = date("Y-m-d 00:00:00", strtotime($row["adviceDate"]));
            }

            array_push($dataInsertSchAM, $dtSAMTemp);
        }


        try {
            $schModel->insertBatch($dataInsertSchAM);

            $getDataInsert = $schModel->getAll(["assetId IN ('" . implode("','", array_column($dataAssetAM, "assetId")) . "')" => null, "DATE_FORMAT(scheduleFrom, '%Y-%m-%d')" => $start, "DATE_FORMAT(scheduleTo, '%Y-%m-%d')" => $end], "scheduleFrom", "asc");

            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success Insert Schedule",
                'data' => $getDataInsert
            ], 200);
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                "status"    => 500,
                "message"   => $e->getMessage(),
            ));
        }
    }
}
