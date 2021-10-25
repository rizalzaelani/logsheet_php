<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;
use DateTime;

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

        $dateTime = new DateTime("2021-10-17");
        $year = $dateTime->format("Y");
        $month = $dateTime->format("n");
        $weekOfYear = $dateTime->format("W");
        $week = $dateTime->format("w");
        // var_dump($year);
        // var_dump($month);
        // var_dump($weekOfYear);
        // var_dump($week);
        // die();
        return $this->template->render('Setting/Schedule/index.php', $data);
    }

    public function datatable()
    {
        $table = 'vw_asset';
        $column_order = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
        $column_search = array('assetId', 'assetName', 'assetNumber', 'tagName', 'tagLocationName', 'description', 'schType', 'createdAt');
        $order = array('createdAt' => 'asc');
        $request = \Config\Services::request();
        $DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);

        $filtTag = explode(",", $_POST["columns"][2]["search"]["value"] ?? '');
        $filtLoc = explode(",", $_POST["columns"][3]["search"]["value"] ?? '');
        $where = [
            'deletedAt' => null,
            'schManual' => '1',
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

    public function updateEvent()
    {
        $assetModel = new AssetModel();
        $scheduleTrxModel = new ScheduleTrxModel();

        $post = $this->request->getJSON();

        $dataAssetId = array_values(array_unique($post->assetId));
        $dataDeselect = $post->deselect;

        $schWeekDays = $post->schWeekDays;
        $schDays = $post->schDays;
        $schWeeks = $post->schWeeks;

        $date = $post->date;
        $schType = $post->schType;
        $lengthDataAssetId = count($dataAssetId);
        $lengthDataDeselect = count($dataDeselect);

        $dateTime = new DateTime($date);
        $year = $dateTime->format("Y");
        $month = $dateTime->format("n");
        $weekOfYear = $dateTime->format("W");
        $day = $dateTime->format("w");

        // delete exist
        if ($lengthDataDeselect > 0) {
            for ($i = 0; $i < $lengthDataDeselect; $i++) {
                $dt = $scheduleTrxModel->where([
                    'assetId' => $dataDeselect[$i]
                ])->delete();
            }
        }

        $schExist = [];
        for ($i = 0; $i < $lengthDataAssetId; $i++) {
            $sch = $scheduleTrxModel->select('assetId')->where(['schManual' => '1', 'assetId' => $dataAssetId[$i]])->get()->getResult();
            $lengthSch = count($sch);
            if ($lengthSch > 0) {
                array_push($schExist, $sch[0]);
            }
        }
        if (count($schExist) == 0) {
            // insert schedule
            if ($schType == 'Daily') {
                $lengthDeselect = count($dataDeselect);

                // insert schedule
                if ($lengthDataAssetId > 0) {
                    for ($i = 0; $i < $lengthDataAssetId; $i++) {
                        $selected = $assetModel->select('assetStatusId')->where('assetId', $dataAssetId[$i])->get()->getResult();
                        $clone = clone $dateTime;

                        $dt = array(
                            'scheduleTrxId' => null,
                            'assetId'       => $dataAssetId[$i],
                            'assetStatusId' => $selected[0]->assetStatusId,
                            'schManual'     => '1',
                            'scheduleFrom'  => $dateTime->format("Y-m-d"),
                            'scheduleTo'  => $clone->modify("+1 days")->format("Y-m-d"),
                        );
                        $scheduleTrxModel->insert($dt);
                    }
                }
            } else if ($schType == 'Weekly') {
                if ($schWeekDays != '') {
                    //insert schedule
                    if ($lengthDataAssetId > 0) {
                        for ($i = 0; $i < $lengthDataAssetId; $i++) {
                            $clone = clone $dateTime;
                            $clone2 = clone $dateTime;
                            $selected = $assetModel->select('assetStatusId')->where('assetId', $dataAssetId[$i])->get()->getResult();
                            $dt = array(
                                'scheduleTrxId' => null,
                                'assetId'       => $dataAssetId[$i],
                                'assetStatusId' => $selected[0]->assetStatusId,
                                'schManual'     => '1',
                                'schType' => $schType,
                                'schWeekDays' => $schWeekDays,
                                'scheduleFrom'  => $clone->modify("-" . $day . "days")->format("Y-m-d"),
                                'scheduleTo'  => $clone2->modify("+" . (7 - $day) . "days")->format("Y-m-d"),
                            );
                            $scheduleTrxModel->insert($dt);
                            // var_dump($dt);
                        }
                    }
                }
            } else {

                // insert schedule
                if ($lengthDataAssetId > 0) {
                    for ($i = 0; $i < $lengthDataAssetId; $i++) {
                        $clone = clone $dateTime;
                        $selected = $assetModel->select('assetStatusId')->where('assetId', $dataAssetId[$i])->get()->getResult();
                        $dt = array(
                            'scheduleTrxId' => null,
                            'assetId' => $dataAssetId[$i],
                            'assetStatusId' => $selected[0]->assetStatusId,
                            'schManual' => '1',
                            'schType' => $schType,
                            'schWeekDays' => $schWeekDays == "" ? NULL : $schWeekDays,
                            'schWeeks' => $schWeeks == "" ? NULL : $schWeeks,
                            'schDays' => $schDays == "" ? NULL : $schDays,
                            'scheduleFrom' => $dateTime->format("Y-m-01"),
                            'scheduleTo' => $clone->modify("+1 month")->format("Y-m-01"),
                        );
                        $scheduleTrxModel->insert($dt);
                        // var_dump($dt);
                    }
                }
            }
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'The Asset is already on the schedule!'));
        }
        die();
    }

    public function schJson()
    {
        $assetModel = new AssetModel();
        $scheduleTrxModel = new ScheduleTrxModel();
        $data = $scheduleTrxModel->where('schManual', '1')->findAll();
        $arr = [];
        foreach ($data as $val) {
            $dataAsset = $assetModel->select('assetName, assetId')->where('assetId', $val['assetId'])->get()->getResult();
            $assetSchType = $val['schType'];
            $dt = [
                'id' => $dataAsset[0]->assetId,
                'title' => $dataAsset[0]->assetName,
                'start' => $val['scheduleFrom'],
                'end' => $val['scheduleTo'],
                'allDay' => false,
            ];
            if ($assetSchType == 'Daily') {
                $dt['backgroundColor'] = '#003399';
                $dt['borderColor'] = '#003399';
                $dt['groupId'] = '1';
                $dt['schType'] = 'Daily';
                $dt['schWeekDays'] = '-';
                $dt['schWeeks'] = '-';
                $dt['schDays'] = '-';
            } else if ($assetSchType == 'Weekly') {
                $dt['backgroundColor'] = '#2eb85c';
                $dt['borderColor'] = '#2eb85c';
                $dt['groupId'] = '2';
                $dt['schType'] = 'Weekly';
                $dt['schWeekDays'] = $val['schWeekDays'];
                $dt['schWeeks'] = '-';
                $dt['schDays'] = '-';
            } else {
                $dt['backgroundColor'] = '#f9b115';
                $dt['borderColor'] = '#f9b115';
                $dt['groupId'] = '3';
                $dt['schType'] = 'Monthly';
                $dt['schWeekDays'] = $val['schWeekDays'] == NULL ? '-' : $val['schWeekDays'];
                $dt['schWeeks'] = $val['schWeeks'] == NULL ? '-' : $val['schWeeks'];
                $dt['schDays'] = $val['schDays'] == NULL ? '-' : $val['schDays'];
            }
            array_push($arr, $dt);
        }
        echo json_encode($arr);
        die();
    }

    public function checkAssetId()
    {
        $scheduleTrxModel = new ScheduleTrxModel();
        $post = $this->request->getJSON();
        $getDate = $post->date;

        $dateTime = new DateTime($getDate);
        $year = $dateTime->format("Y");
        $month = $dateTime->format("n");
        $weekOfYear = $dateTime->format("W");
        $day = $dateTime->format("w");

        $whereSch = "((schType = 'Daily' AND DATE(scheduleFrom) = '" . $dateTime->format("Y-m-d") . "') OR (schType = 'Weekly' AND WEEK(scheduleFrom) = " . ($day == 0 ? ($weekOfYear + 1) : $weekOfYear) . " AND YEAR(scheduleFrom) = " . $year . ") OR (schType = 'Monthly' AND MONTH(scheduleFrom) = " . $month . " AND YEAR(scheduleFrom) = " . $year . "))";

        $getDataSch = $scheduleTrxModel->getAll(["schManual" => "1", $whereSch => null]);
        $schExist = [];
        foreach ($getDataSch as $row) {
            array_push($schExist, $row['assetId']);
        }
        echo json_encode($schExist);
        die();
    }
}
