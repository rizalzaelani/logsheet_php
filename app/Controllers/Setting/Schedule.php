<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Exception;

class Schedule extends BaseController
{
    public function index()
    {
        if(!checkRoleList("SCHEDULE.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

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

        $data["assetManualData"] = $assetModel->getAll(['userId' => $this->session->get("adminId"), "schManual" => "1", "deletedAt IS NULL" => null]);

        return $this->template->render('Setting/Schedule/index.php', $data);
    }

    public function getDataByMonth()
    {
        if(!checkRoleList("SCHEDULE.LIST")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $month = $this->request->getVar('month') ?? date("m");
        $year = $this->request->getVar('year') ?? date("Y");

        $where = [
			"userId" => $this->session->get("adminId"),
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
        if(!checkRoleList("SCHEDULE.ADD")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

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

        $getLastAssetId = $schModel->getAll(["assetId IN ('" . implode("','", array_column($dataAssetAM, "assetId")) . "')" => null, "scheduleTo >=" => $start ], "scheduleTo", "desc");
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

            if (!empty($row["adviceDate"])) {
                $dtSAMTemp["adviceDate"] = date("Y-m-d 00:00:00", strtotime($row["adviceDate"]));
            } else {
                $dtSAMTemp["adviceDate"] = null;
            }

            array_push($dataInsertSchAM, $dtSAMTemp);
        }


        try {
            $schModel->insertBatch($dataInsertSchAM);

            $activity = 'Add schedule asset manual';
            sendLog($activity, null, json_encode($dataInsertSchAM));

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

    public function importSchedule()
    {
        if(!checkRoleList("SCHEDULE.IMPORT")){
            return $this->response->setJson([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

		$file = $this->request->getFile('importSch');
		if ($file) {
			$newName = "IS_" . time() . '.xlsx';
			$file->move('upload/', $newName);
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('upload/' . $newName);

			$dataImport = [];
            $i = 0;
			foreach ($reader->getSheetIterator() as $sheet) {
                if($i == 0){
                    $dataImport["start"] = null;
                    $dataImport["end"] = null;
                    $dataImport["data"] = [];
                    $numrow = 1;
                    foreach ($sheet->getRowIterator() as $row) {
                        if($numrow == 1) $dataImport["start"] = $row->getCellAtIndex(1)->getValue()->format("Y-m-d");
                        if($numrow == 2) $dataImport["end"] = $row->getCellAtIndex(1)->getValue()->format("Y-m-d");

                        if ($numrow > 3) {
                            if ($row->getCellAtIndex(1)->getValue() != '' & $row->getCellAtIndex(1)->getValue() != null) {
                                $dataImport["data"][] = array(
                                    'assetNumber' => $row->getCellAtIndex(0)->getValue(),
                                    'adviceScan' => $row->getCellAtIndex(1)->getValue()->format("Y-m-d")
                                );
                            }
                        }
                        $numrow++;
                    }
                }
                $i++;
			}

            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success Import Data",
                'data' => $dataImport
            ], 200);
        } else {
            return $this->response->setJSON([
                'status' => 400,
                'message' => "No such File Uploaded",
                'data' => []
            ], 400);
        }
    }
}
