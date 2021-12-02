<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AssetTagLocationModel;
use App\Models\TagLocationModel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Location extends BaseController
{
    public function index()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.VIEW")) {
            return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
        }

        $data = array(
            'title' => 'Tag Location',
            'subtitle' => 'List Location',
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Tag Location",
                "link"    => "Tag Location"
            ],
        ];

        return $this->template->render('Master/Location/index', $data);
    }

    public function datatable()
    {
        $request = \Config\Services::request();

        if (!checkRoleList("MASTER.TAGLOCATION.VIEW")) {
            echo json_encode(array(
                "draw" => $request->getPost('draw'),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                'status' => 403,
                'message' => "You don't have access to this page"
            ));
        }

        $table = "tblm_tagLocation";
        $column_order = array('tagLocationName', 'latitude', 'longitude', 'description', 'createdAt');
        $column_search = array('tagLocationName', 'latitude', 'longitude', 'description', 'createdAt');
        $order = array('createdAt' => 'asc');
        $DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
        $where = [
            'userId' => $this->session->get("adminId"),
        ];
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
        if (!checkRoleList("MASTER.TAGLOCATION.DETAIL")) {
            return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
        }

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

    public function add()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.ADD")) {
            return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
        }

        $model = new TagLocationModel();
        $data = array(
            'title' => 'Add Tag Location',
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Tag Location",
                "link"    => "Location"
            ],
            [
                "title"    => "Add",
                "link"    => "add"
            ],
        ];
        return $this->template->render('Master/Location/add', $data);
    }

    public function addTagLocation()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.ADD")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $model = new TagLocationModel();
        $json = $this->request->getJSON();
        if ($json->tagLocationName != '') {
            $data = array(
                'tagLocationName' => $json->tagLocationName,
                'userId' => $this->session->get("adminId"),
                'latitude' => $json->latitude,
                'longitude' => $json->longitude,
                'description' => $json->description
            );
            $model->insert($data);
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'Field location name cannot be empty!'));
        }
        die();
    }

    public function update()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.UPDATE")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }
        $model = new TagLocationModel();
        $json = $this->request->getJSON();
        $id = $json->tagLocationId;
        if (isset($json)) {
            $data = array(
                'userId' => $this->session->get('adminId'),
                'tagLocationName' => $json->tagLocationName,
                'latitude' => $json->latitude,
                'longitude' => $json->longitude,
                'description' => $json->description
            );
            $model->update($id, $data);
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
        }
        die();
    }

    public function delete()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.DELETE")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $locationModel = new TagLocationModel();
        $assetLocationModel = new AssetTagLocationModel();
        $json = $this->request->getJSON();
        $tagLocationId = $json->tagLocationId;
        if ($tagLocationId != '') {
            $assetLocationModel->deleteTagLocationId($tagLocationId);
            $locationModel->delete($tagLocationId);
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data', 'data' => $json));
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $json));
        }
        die();
    }

    public function download()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.IMPORT")) {
            return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
        }

        return $this->response->download($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . 'download/location.xlsx', null);
    }
    public function uploadFile()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.IMPORT")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $file = $this->request->getFile('fileImportLocation');
        if ($file) {
            $newName = "doc" . time();
            $file->move('../uploads', $newName);
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open('../uploads/' . $newName);
            $dataImport = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                $numrow = 1;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($numrow > 1) {
                        if ($row->getCellAtIndex(1) != '' && $row->getCellAtIndex(2) != '') {
                            $dataImport[] = array(
                                'locationName' => $row->getCellAtIndex(1)->getValue(),
                                'latitude' => $row->getCellAtIndex(2)->getValue(),
                                'longitude' => $row->getCellAtIndex(3)->getValue(),
                                'description' => $row->getCellAtIndex(4)->getValue(),
                            );
                        } else {
                            return $this->response->setJSON(array('status' => 'failed', 'message' => 'Data Does Not Match'));
                        }
                    }
                    $numrow++;
                }
            }
            unlink('../uploads/' . $newName);
            if ($dataImport) {
                return $this->response->setJSON(array('status' => 'success', 'message' => '', 'data' => $dataImport));
            } else {
                return $this->response->setJSON(array('status' => 'failed', 'message' => 'Data Not Found!'));
            }
        } else {
            return $this->response->setJSON((array('status' => 'failed', 'message' => 'Bad Request!')));
        }
    }

    public function insertLocation()
    {
        if (!checkRoleList("MASTER.TAGLOCATION.IMPORT")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }
        $tagLocationModel = new TagLocationModel();
        $json = $this->request->getJSON();
        $dataLocation = $json->dataLocation;
        $length = count($dataLocation);
        for ($i = 0; $i < $length; $i++) {
            $data = [
                'tagLocationId' => null,
                'userId' => $this->session->get('adminId'),
                'tagLocationName'   => $dataLocation[$i]->locationName,
                'latitude'   => $dataLocation[$i]->latitude,
                'longitude'   => $dataLocation[$i]->longitude,
                'description'   => $dataLocation[$i]->description,
            ];
            $tagLocationModel->insert($data);
        }
        echo json_encode(array('status' => 'success', 'message' => '', 'data' => $json));
        die();
    }
}
