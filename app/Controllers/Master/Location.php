<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\TagLocationModel;

class Location extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Location',
            'subtitle' => 'List Location',
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Location",
                "link"    => "Location"
            ],
        ];

        return $this->template->render('Master/Location/index', $data);
    }

    public function datatable()
    {
        $table = "tblm_tagLocation";
        $column_order = array('tagLocationName', 'latitude', 'longitude', 'description', 'createdAt');
        $column_search = array('tagLocationName', 'latitude', 'longitude', 'description', 'createdAt');
        $order = array('createdAt' => 'asc');
        $request = \Config\Services::request();
        $DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
        $where = [];
        $list = $DTModel->datatable($where);
        $output = array(
            "draw" => $request->getPost('draw'),
            "recordsTotal" => $DTModel->count_all($where),
            "recordsFiltered" => $DTModel->count_filtered($where),
            "data" => $list,
            "status" => 200,
            "message" => "success"
        );
        echo json_encode($output);
    }

    public function detail($tagLocationId)
    {
        $model = new TagLocationModel();
        $location = $model->where('tagLocationId', $tagLocationId)->first();
        $data = array(
            'title' => 'Detail Location',
        );
        $data['location'] = $location;
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Location",
                "link"    => "Location"
            ],
            [
                "title"    => "Detail",
                "link"    => "detail"
            ],
        ];
        return $this->template->render('Master/Location/detail', $data);
    }

    public function update()
    {
        $model = new TagLocationModel();
        $json = $this->request->getJSON();
        $id = $json->tagLocationId;
        if (isset($json)) {
            $data = array(
                'tagLocationName' => $json->tagLocationName,
                'latitude' => $json->latitude,
                'longitude' => $json->longitude,
                'description' => $json->description
            );
            $model->update($id, $data);
            echo json_encode(array('status' => 'success', 'message' => 'success add tag', 'data' => $data));
            die();
        }
    }

    public function delete()
    {
        $model = new TagLocationModel();
        $where = $this->request->getJSON('tagLocationId');
        if (isset($where)) {
            $model->delete($where);
            echo json_encode(array('status' => 'success', 'message' => 'success delete data', 'data' => $where));
            die();
        }
    }
}
