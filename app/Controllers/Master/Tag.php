<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\TagModel;

class Tag extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Tag',
            'subtitle' => 'List Tag',
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Tag",
                "link"    => "Tag"
            ],
        ];

        return $this->template->render('Master/Tag/index', $data);
    }

    public function datatable()
    {
        $table = "tblm_tag";
        $column_order = array('tagId', 'tagName', 'description', 'createdAt');
        $column_search = array('tagId', 'tagName', 'description', 'createdAt');
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

    public function add()
    {
        $model = new TagModel();
        $data = $this->request->getJSON();
        if ($data->tagName != '' && $data->description != '') {
            $dt = array(
                'tagId' => $data->tagId,
                'userId' => $data->userId,
                'tagName' => $data->tagName,
                'description' => $data->description
            );
            $model->insert($dt);
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully added tag.', 'data' => $dt));
        }else{
            echo json_encode(array('status' => 'failed', 'message' => 'All fields cannot be empty'));
        }
        die();
    }
}
