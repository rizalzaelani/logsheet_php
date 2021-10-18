<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\ScheduleTrxModel;

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
        $lengthDeselect = count($dataDeselect);
        $schExist = $scheduleTrxModel->select('assetId')->where('scheduleFrom', $post->date)->findAll();
        $lengthExist = count($schExist);
        $arrExist = [];
        for ($i = 0; $i < $lengthExist; $i++) {
            array_push($arrExist, $schExist[$i]['assetId']);
        }
        // delete
        if ($lengthDeselect > 0) {
            for ($i = 0; $i < $lengthDeselect; $i++) {
                $scheduleTrxModel->where(['scheduleFrom' => $post->date, 'assetId' => $dataDeselect[$i]])->delete();
            }
        }
        // data post
        $arrNew = [];
        for ($i = 0; $i < count($dataAssetId); $i++) {
            array_push($arrNew, $dataAssetId[$i]);
        }

        // insert not exist schedule 
        $dif = array_values(array_diff($arrNew, $arrExist));
        $lengthDif = count($dif);
        if ($lengthDif > 0) {
            for ($i = 0; $i < $lengthDif; $i++) {
                $selected = $assetModel->select('assetStatusId')->where('assetId', $dif[$i])->get()->getResult();
                $dt = array(
                    'scheduleTrxId' => null,
                    'assetId'       => $dif[$i],
                    'assetStatusId' => $selected[0]->assetStatusId,
                    'schManual'     => '1',
                    'scheduleFrom'  => $post->date,
                    'scheduleTo'  => $post->date . ' 23:59:59',
                );
                $scheduleTrxModel->insert($dt);
            }
        }
        die();
    }

    public function schJson()
    {
        $assetModel = new AssetModel();
        $scheduleTrxModel = new ScheduleTrxModel();
        $data = $scheduleTrxModel->where('schManual', '1')->findAll();
        $lengthData = count($data);
        $arr = [];
        foreach ($data as $val) {
            $dataAsset = $assetModel->select('assetName')->where('assetId', $val['assetId'])->get()->getResult();
            $dt = [
                'title' => $dataAsset[0]->assetName,
                // 'start' => date("Y-m-d", strtotime($val['scheduleFrom'])),
                // 'end' => date("Y-m-d", strtotime($val['scheduleTo'])),
                'start' => $val['scheduleFrom'],
                'end' => $val['scheduleTo'],
                'allDay' => true,
                'backgroundColor' => 'orange',
                'borderColor' => 'orange'
            ];
            array_push($arr, $dt);
        }
        echo json_encode($arr);
        die();
    }

    public function checkAssetId()
    {
        $scheduleTrxModel = new ScheduleTrxModel();
        $post = $this->request->getJSON();
        $dateStr = $post->date . ' 00:00:00';
        $data = $scheduleTrxModel->select('assetId')->where(['scheduleFrom' => $dateStr, 'schManual' => '1'])->get()->getResult();
        echo json_encode($data);
        die();
    }
}
