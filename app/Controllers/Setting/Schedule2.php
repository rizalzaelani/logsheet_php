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
        $schFrequency = $post->schFrequency;
        $schWeekDays = $post->schWeekDays;

        $date = $post->date;
        $schType = $post->schType;
        $schFrom = $post->schFrom;
        $schTo = $post->schTo;

        $schExist = [];
        $scheduleFrom = [];
        $scheduleTo = [];

        // new schedule / new assetid
        $postAssetId = [];
        for ($i = 0; $i < count($dataAssetId); $i++) {
            array_push($postAssetId, $dataAssetId[$i]);
        }


        if ($schType != '') {
            if ($schType == 'Daily') {

                $interval = 24 / $schFrequency;
                $hour = 24;

                for ($a = 0; $a < $hour; $a += $interval) {
                    if ($a < 10) {
                        $dtFrom = '0' . $a . ':00:00';
                        $dateFrom = $date . ' ' . $dtFrom;
                        $dayPlus = date("Y-m-d", strtotime($date . "+1 days"));
                        $dateTo = ((int)($a + $interval) == 24 ? ($dayPlus . ' ' . "00") : ($date . ' ' . (($a + $interval) >= 10 ? ($a + $interval) : ('0' . ($a + $interval))))) . ':00:00';
                        array_push($scheduleFrom, $dateFrom);
                        array_push($scheduleTo, $dateTo);
                    } else {
                        $dateFrom = $date . ' ' . $a . ':00:00';
                        $dayPlus = date("Y-m-d", strtotime($date . "+1 days"));
                        $dateTo = ((int)($a + $interval) == 24 ? ($dayPlus . ' ' . "00") : ($date . ' ' . ($a + $interval))) . ':00:00';
                        array_push($scheduleFrom, $dateFrom);
                        array_push($scheduleTo, $dateTo);
                    }
                }

                $data = $scheduleTrxModel->select('assetId')->where(['scheduleFrom' => $date, 'schType' => $schType])->get()->getResult();
                if (count($data) > 0) {
                    for ($a = 0; $a < count($data); $a++) {
                        array_push($schExist, $data[$a]->assetId);
                    }
                }
                $lengthDeselect = count($dataDeselect);
                $lengthExist = count($schExist);

                // delete schedule
                if ($lengthDeselect > 0) {
                    for ($i = 0; $i < $lengthDeselect; $i++) {
                        for ($b = 0; $b < $schFrequency; $b++) {
                            $dt = $scheduleTrxModel->where([
                                'schType' => 'Daily',
                                'schFrequency' => $schFrequency,
                                'scheduleFrom' => $scheduleFrom[$b],
                                'scheduleTo' => $scheduleTo[$b],
                                'assetId' => $dataDeselect[$i]
                            ])->delete();
                        }
                    }
                }

                // get not exist schedule 
                $schNew = array_values(array_diff($postAssetId, $schExist));
                $lengthSchNew = count($schNew);

                // insert schedule
                if ($lengthSchNew > 0) {
                    for ($i = 0; $i < $lengthSchNew; $i++) {
                        for ($b = 0; $b < $schFrequency; $b++) {
                            $selected = $assetModel->select('assetStatusId')->where('assetId', $schNew[$i])->get()->getResult();
                            $dt = array(
                                'scheduleTrxId' => null,
                                'assetId'       => $schNew[$i],
                                'assetStatusId' => $selected[0]->assetStatusId,
                                'schManual'     => '1',
                                'schFrequency' => $schFrequency,
                                'scheduleFrom'  => $scheduleFrom[$b],
                                'scheduleTo'  => $scheduleTo[$b],
                            );
                            $scheduleTrxModel->insert($dt);
                        }
                    }
                }
            } else if ($schType == 'Weekly') {
                if ($schWeekDays != '') {
                    $data = $scheduleTrxModel->select('assetId')->where([
                        'schType' => 'Weekly',
                        'schWeekDays' => $schWeekDays,
                        'scheduleFrom' => $schFrom,
                        'scheduleTo' => $schTo,
                    ])->get()->getResult();
                    if (count($data) > 0) {
                        for ($i = 0; $i < count($data); $i++) {
                            array_push($schExist, $data[$i]->assetId);
                        }
                    }

                    //delete schedule
                    if (count($dataDeselect) > 0) {
                        for ($i = 0; $i < count($dataDeselect); $i++) {
                            $dt = $scheduleTrxModel->where([
                                'schType' => 'Weekly',
                                'schWeekDays' => $schWeekDays,
                                'scheduleFrom' => $schFrom,
                                'scheduleTo' => $schTo,
                                'assetId' => $dataDeselect[$i]
                            ])->delete();
                        }
                    }

                    //insert schedule
                    $dif = array_values(array_diff($postAssetId, $schExist));
                    if (count($dif) > 0) {
                        for ($i = 0; $i < count($dif); $i++) {
                            $selected = $assetModel->select('assetStatusId')->where('assetId', $dif[$i])->get()->getResult();
                            $dt = array(
                                'scheduleTrxId' => null,
                                'assetId'       => $dif[$i],
                                'assetStatusId' => $selected[0]->assetStatusId,
                                'schManual'     => '1',
                                'schType' => $schType,
                                'schWeekDays' => $schWeekDays,
                                'scheduleFrom'  => $schFrom,
                                'scheduleTo'  => $schTo,
                            );
                            $scheduleTrxModel->insert($dt);
                        }
                    }
                }
            } else {
                $schDays = $post->schDays;
                $schWeeks = $post->schWeeks;
                $data = $scheduleTrxModel->select('assetId')->where([
                    'schType' => $schType,
                    'schWeekDays' => $schWeekDays == "" ? NULL : $schWeekDays,
                    'schDays' => $schDays == "" ? NULL : $schDays,
                    'schWeeks' => $schWeeks == "" ? NUll : $schWeeks,
                ])->get()->getResult();
                if (count($data) > 0) {
                    for ($i = 0; $i < count($data); $i++) {
                        array_push($schExist, $data[$i]->assetId);
                    }
                }

                // delete schedule
                if (count($dataDeselect) > 0) {
                    for ($i = 0; $i < count($dataDeselect); $i++) {
                        $dt = $scheduleTrxModel->where([
                            'schType' => $schType,
                            'schWeekDays' => $schWeekDays == "" ? NULL : $schWeekDays,
                            'schWeeks' => $schWeeks == "" ? NULL : $schWeeks,
                            'schDays' => $schDays == "" ? NULL : $schDays,
                            'scheduleFrom' => $schFrom,
                            'scheduleTo' => $schTo,
                        ])->delete();
                    }
                }

                // insert schedule
                $dif = array_values(array_diff($postAssetId, $schExist));
                if (count($dif) > 0) {
                    for ($i = 0; $i < count($dif); $i++) {
                        $selected = $assetModel->select('assetStatusId')->where('assetId', $dif[$i])->get()->getResult();
                        $dt = array(
                            'scheduleTrxId' => null,
                            'assetId' => $dif[$i],
                            'assetStatusId' => $selected[0]->assetStatusId,
                            'schManual' => '1',
                            'schType' => $schType,
                            'schWeekDays' => $schWeekDays == "" ? NULL : $schWeekDays,
                            'schWeeks' => $schWeeks == "" ? NULL : $schWeeks,
                            'schDays' => $schDays == "" ? NULL : $schDays,
                            'scheduleFrom' => $schFrom,
                            'scheduleTo' => $schTo,
                        );
                        $scheduleTrxModel->insert($dt);
                    }
                }
            }
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!'));
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
        $dateStr = $post->date;
        $date = date("Y-m-d", strtotime($dateStr));
        $schType = $post->schType;

        $assetId = [];
        if ($schType == 'Daily') {
            $schFrequency = $post->schFrequency;
            $interval = 24 / $schFrequency;
            $hour = 24;
            for ($i = 0; $i < $hour; $i += $interval) {
                if ($i < 10) {
                    $dtFrom = '0' . $i . ':00:00';
                    $dtTo = ((int)(0 . ($i + $interval)) >= 10 ? ($i + $interval) : ('0' . ($i + $interval))) . ':00:00';
                    $dateFrom = $date . ' ' . $dtFrom;
                    $dayPlus = date("Y-m-d", strtotime($date . "+1 days"));
                    $dateTo = ((int)($i + $interval) == 24 ? ($dayPlus . ' ' . "00") : ($date . ' ' . (($i + $interval) >= 10 ? ($i + $interval) : ('0' . ($i + $interval))))) . ':00:00';
                    $result = $scheduleTrxModel->select('assetId')->where(['schManual' => '1', 'schType' => $schType, 'scheduleFrom' => $dateFrom, 'scheduleTo' => $dateTo])->get()->getResult();
                    if ($result != null) {
                        for ($b = 0; $b < count($result); $b++) {
                            array_push($assetId, $result[$b]->assetId);
                        }
                    }
                } else {
                    $dateFrom = $date . ' ' . $i . ':00:00';
                    $dayPlus = date("Y-m-d", strtotime($date . "+1 days"));
                    $dateTo = ((int)($i + $interval) == 24 ? ($dayPlus . ' ' . "00") : ($date . ' ' . ($i + $interval))) . ':00:00';
                    $result = $scheduleTrxModel->select('assetId')->where(['schManual' => '1', 'schType' => $schType, 'scheduleFrom' => $dateFrom, 'scheduleTo' => $dateTo])->get()->getResult();
                    if ($result != null) {
                        for ($b = 0; $b < count($result); $b++) {
                            array_push($assetId, $result[$b]->assetId);
                        }
                    }
                }
            }
        } else if ($schType == 'Weekly') {
            $schWeekDays = $post->schWeekDays;
            $schFrom = date("Y-m-d H:i:s", strtotime($post->schFrom));
            $schTo = date("Y-m-d H:i:s", strtotime($post->schTo));
            if ($schType != '' && $schWeekDays != '' && $schFrom != '' && $schTo != '') {
                $result = $scheduleTrxModel->select('assetId')->where([
                    'schManual' => '1',
                    'scheduleFrom' => $schFrom,
                    'scheduleTo' => $schTo,
                    'schTYpe' => $schType,
                    'schWeekDays' => $schWeekDays
                ])->get()->getResult();
                if ($result != null) {
                    for ($i = 0; $i < count($result); $i++) {
                        array_push($assetId, $result[$i]->assetId);
                    }
                }
            } else {
                echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!'));
            }
        } else {
            $schDays = $post->schDays;
            $schWeeks = $post->schWeeks;
            $schWeekDays = $post->schWeekDays;
            $schFrom = date("Y-m-d H:i:s", strtotime($post->schFrom));
            $schTo = date("Y-m-d H:i:s", strtotime($post->schTo));
            if ($schType != '' && $schFrom != '' && $schTo != '') {
                $result = $scheduleTrxModel->select('assetId')->where([
                    'schManual' => '1',
                    'schType' => $schType,
                    'scheduleFrom' => $schFrom,
                    'scheduleTo' => $schTo,
                    'schDays' => $schDays == "" ? NULL : $schDays,
                    'schWeekDays' => $schWeekDays == "" ? NULL : $schWeekDays,
                    'schWeeks' => $schWeeks == "" ? NULL : $schWeeks,
                ])->get()->getResult();
                if ($result != null) {
                    for ($i = 0; $i < count($result); $i++) {
                        array_push($assetId, $result[$i]->assetId);
                    }
                }
            }
        }
        echo json_encode($assetId);
        die();
    }
}
