<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;
use DateTime;
use Exception;

class ScheduleTrx extends BaseController
{
    public function generateManual()
    {
		$data = array(
			'title' => 'Schedule',
			'subtitle' => 'Schedule'
		);

		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Schedule",
				"link"	=> "scheduleTrx/generateManual"
			],
		];
		return $this->template->render('Transaction/ScheduleTrx/generateManual', $data);
    }

    public function generateSchedule()
    {
        $assetModel = new AssetModel();
        $scheduleTrxModel = new ScheduleTrxModel();

        $dateTime = new DateTime();
        $year = $dateTime->format("Y");
        $month = $dateTime->format("n");
        $weekOfYear = $dateTime->format("W");
        $weekDay = $dateTime->format("w");

        $getDataAsset = $assetModel->getAll(["userId" => $this->session->get("adminId"), "schManual" => "0","deletedAt IS NULL" => null]);
        $getDataSchMonthly = $scheduleTrxModel->getAll(["userId" => $this->session->get("adminId"), "schType" => "Monthly", "schManual" => "0", "MONTH(scheduleFrom)" => $month, "YEAR(scheduleFrom)" => $year]);
        $getDataSchWeekly = $scheduleTrxModel->getAll(["userId" => $this->session->get("adminId"), "schType" => "Weekly", "schManual" => "0", "WEEKOFYEAR(scheduleFrom)" => $weekOfYear, "YEAR(scheduleFrom)" => $year]);
        $getDataSchDaily = $scheduleTrxModel->getAll(["userId" => $this->session->get("adminId"), "schType" => "Daily", "schManual" => "0", "DATE(scheduleFrom)" => $dateTime->format("Y-m-d")]);

        $dataInsertSch = [];
        $existScheduleM = [];
        $addScheduleM = [];
        foreach ($getDataAsset as $row) {
            if ($row["schType"] == "Monthly") {
                $cekSch = array_filter($getDataSchMonthly, function ($val) use ($row) {
                    return $val["assetId"] == $row["assetId"];
                });
                if (empty($cekSch)) {
                    $dateMonth = new DateTime($dateTime->format("Y-m-d"));
                    array_push($dataInsertSch, array(
                        "scheduleTrxId" => null,
                        "assetId"       => $row["assetId"],
                        "assetStatusId" => $row["assetStatusId"],
                        "schManual"     => $row["schManual"],
                        "schType"       => $row["schType"],
                        "schFrequency"  => $row["schFrequency"],
                        "schWeeks"      => $row["schWeeks"],
                        "schWeekDays"   => $row["schWeekDays"],
                        "schDays"       => $row["schDays"],
                        "scheduleFrom"  => $dateMonth->format("Y-m-01 00:00:00"),
                        "scheduleTo"    => $dateMonth->format("Y-m-t 23:59:59"),
                        "condition"     => "Normal",
                    ));

                    $addScheduleM[] = $row["assetName"];
                } else {
                    $existScheduleM[] = $row["assetName"];
                }
            } else if ($row["schType"] == "Weekly") {
                $cekSch = array_filter($getDataSchWeekly, function ($val) use ($row) {
                    return $val["assetId"] == $row["assetId"];
                });
                if (empty($cekSch)) {
                    $dateWeek = new DateTime($dateTime->format("Y-m-d"));
                    array_push($dataInsertSch, array(
                        "scheduleTrxId" => null,
                        "assetId"       => $row["assetId"],
                        "assetStatusId" => $row["assetStatusId"],
                        "schManual"     => $row["schManual"],
                        "schType"       => $row["schType"],
                        "schFrequency"  => $row["schFrequency"],
                        "schWeeks"      => $row["schWeeks"],
                        "schWeekDays"   => $row["schWeekDays"],
                        "schDays"       => $row["schDays"],
                        "scheduleFrom"  => $dateWeek->modify('-' . $weekDay . ' days')->format("Y-m-d 00:00:00"),
                        "scheduleTo"    => $dateWeek->modify('+' . (6 - $weekDay) . ' days')->format("Y-m-d 23:59:59"),
                        "condition"     => "Normal",
                    ));

                    $addScheduleM[] = $row["assetName"];
                } else {
                    $existScheduleM[] = $row["assetName"];
                }
            } else if ($row["schType"] == "Daily") {
                $cekSch = array_filter($getDataSchDaily, function ($val) use ($row) {
                    return $val["assetId"] == $row["assetId"];
                });
                if (empty($cekSch)) {
                    $freq = ($row['schFrequency'] ?? 1);
                    $hour = 24 / $freq;
                    for ($i = 0; $i < $freq; $i++) {
                        $lt = $hour * $i;
                        $dateFrom = (new DateTime($dateTime->format("Y-m-d")))->modify("+" . $lt . " Hours");
                        $dateTo = (new DateTime($dateFrom->format("Y-m-d H:i:s")))->modify("+" . $hour . " Hours");

                        array_push($dataInsertSch, array(
                            "scheduleTrxId" => null,
                            "assetId"       => $row["assetId"],
                            "assetStatusId" => $row["assetStatusId"],
                            "schManual"     => $row["schManual"],
                            "schType"       => $row["schType"],
                            "schFrequency"  => $row["schFrequency"],
                            "schWeeks"      => $row["schWeeks"],
                            "schWeekDays"   => $row["schWeekDays"],
                            "schDays"       => $row["schDays"],
                            "scheduleFrom"  => $dateFrom->format("Y-m-d H:i:s"),
                            "scheduleTo"    => $dateTo->format("Y-m-d H:i:s"),
                            "condition"     => "Normal",
                        ));

                        $addScheduleM[] = $row["assetName"];
                    }
                } else {
                    $existScheduleM[] = $row["assetName"];
                }
            }
        }

        try {
            if (!empty($dataInsertSch)) {
                $scheduleTrxModel->insertBatch($dataInsertSch);
            }

            return $this->response->setJSON(array(
                "status"    => 200,
                "message"   => "Success Get Timeline",
                // "data"      => $dataInsertSch
                "data"      => array(
                    "Success Add Schedule"      => $addScheduleM,
                    "Schedule Already Exist"    => $existScheduleM,
                )
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                "status"    => 500,
                "message"   => $e->getMessage(),
            ));
        }
    }
}
