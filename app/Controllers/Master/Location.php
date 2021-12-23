<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AssetTagLocationModel;
use App\Models\Influx\LogModel;
use App\Models\TagLocationModel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use DateTime;
use Exception;

class Location extends BaseController
{
    public function index()
    {
        if(!checkRoleList("MASTER.TAGLOCATION.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

        // $influxModel = new LogModel();
        // $from = new DateTime();
        // $to = new DateTime();
        // $dateFrom = $from->format("Y-m-d H:i:s");
        // $dateTo = $from->modify("+1 days")->format("Y-m-d H:i:s");
        // $test = $influxModel->getAll($dateFrom, $dateFrom);
        // d($test);
        // die();

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
        if(!checkRoleList("MASTER.TAGLOCATION.DETAIL")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
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
        if(!checkRoleList("MASTER.TAGLOCATION.ADD")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
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

        $model          = new TagLocationModel();
        $influxModel    = new LogModel();

        $activity       = 'Add Tag Location';
        $ipAddress      = $this->request->getIPAddress();
        $username       = $this->session->get('name');
        $userId         = $this->session->get('adminId');

        $json = $this->request->getJSON();

        try {
            $data = array(
                'tagLocationName' => $json->tagLocationName,
                'userId' => $this->session->get("adminId"),
                'latitude' => $json->latitude,
                'longitude' => $json->longitude,
                'description' => $json->description
            );
            $model->insert($data);
            $influxModel->writeData($activity, $ipAddress, $userId, $username, null, null);

            return $this->response->setJSON(array(
                'status' => 200,
                'message'=> 'You have successfully add data',
                'data'   => $data
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message'=> $e->getMessage(),
                'data'   => ""
            ));
        }
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
        $influxModel    = new LogModel();

        $activity       = 'Update Tag Location';
        $ipAddress      = $this->request->getIPAddress();
        $username       = $this->session->get('name');
        $userId         = $this->session->get('adminId');

        $json = $this->request->getJSON();
        $tagLocationId = $json->tagLocationId;

        try {
            $data = array(
                'userId' => $this->session->get('adminId'),
                'tagLocationName' => $json->tagLocationName,
                'latitude' => $json->latitude,
                'longitude' => $json->longitude,
                'description' => $json->description
            );
            $model->update($tagLocationId, $data);
            $influxModel->writeData($activity, $ipAddress, $userId, $username, null, null);

            return $this->response->setJSON(array(
                'status' => 200,
                'message'=> 'You have successfully updated data',
                'data'   => $data
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message'=> $e->getMessage(),
                'data'   => ""
            ));
        }
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
        $influxModel    = new LogModel();

        $activity       = 'Delete Tag Location';
        $ipAddress      = $this->request->getIPAddress();
        $username       = $this->session->get('name');
        $userId         = $this->session->get('adminId');

        $json = $this->request->getJSON();
        $tagLocationId = $json->tagLocationId;

        try {
            $assetLocationModel->deleteTagLocationId($tagLocationId);
            $locationModel->delete($tagLocationId);
    
            $influxModel->writeData($activity, $ipAddress, $userId, $username, null, null);

            return $this->response->setJSON(array(
                'status' => 200,
                'message'=> 'You have successfully deleted data',
                'data'   => $json
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message'=> $e->getMessage(),
                'data'   => ""
            ));
        }
    }

    public function download()
    {
        if(!checkRoleList("MASTER.TAGLOCATION.IMPORT")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }
        $influxModel    = new LogModel();

        $activity       = 'Download Template Tag Location';
        $ipAddress      = $this->request->getIPAddress();
        $username       = $this->session->get('name');
        $userId         = $this->session->get('adminId');

        $influxModel->writeData($activity, $ipAddress, $userId, $username, null, null);
        try {
            return $this->response->download($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . 'download/location.xlsx', null);
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message'=> $e->getMessage(),
                'data'   => ""
            ));
        }
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
            $file->move('upload/', $newName);
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open('upload/' . $newName);
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
            unlink('upload/' . $newName);
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
        $influxModel    = new LogModel();

        $activity       = 'Import Tag Location';
        $ipAddress      = $this->request->getIPAddress();
        $username       = $this->session->get('name');
        $userId         = $this->session->get('adminId');

        $json = $this->request->getJSON();
        $dataLocation = $json->dataLocation;
        $length = count($dataLocation);

        try {
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
            $influxModel->writeData($activity, $ipAddress, $userId, $username, null, null);

            return $this->response->setJSON(array(
                'status' => 200,
                'message'=> 'You have successfully import data',
                'data'   => $json
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message'=> $e->getMessage(),
                'data'   => ""
            ));
        }
    }

    public function exportExcel()
    {
        $tagLocationModel = new TagLocationModel();
        $influxModel    = new LogModel();

        $activity       = 'Export Tag Location';
        $ipAddress      = $this->request->getIPAddress();
        $username       = $this->session->get('name');
        $userId         = $this->session->get('adminId');

        try {
            $writer = WriterEntityFactory::createXLSXWriter();
            $userId = $this->session->get('adminId');
            $data = $tagLocationModel->where('userId', $userId)->findAll();
    
            $writer->setShouldUseInlineStrings(true);
            $header = ["No", "Tag Location", "Latitude", "Longitude", "Description"];
            $styleHeader = (new StyleBuilder())
                ->setCellAlignment(CellAlignment::CENTER)
                ->setBackgroundColor(COLOR::YELLOW)
                ->build();
            $styleBody = (new StyleBuilder())
                ->setCellAlignment(CellAlignment::LEFT)
                ->build();
            $dataArr = [];
    
            if (count($data)) {
                foreach ($data as $key => $value) {
                    $arr = [$key + 1, $value['tagLocationName'], $value['latitude'], $value['longitude'], $value['description']];
                    array_push($dataArr, $arr);
                }
            }
            $fileName = "Tag Location - " . date("d M Y") . '.xlsx';
            $writer->openToBrowser($fileName);
    
            $rowFromValues = WriterEntityFactory::createRowFromArray($header, $styleHeader);
            $writer->addRow($rowFromValues);
            foreach ($dataArr as $key => $value) {
                $rowFromValues = WriterEntityFactory::createRowFromArray($value, $styleBody);
                $writer->addRow($rowFromValues);
            }
            $writer->close();
            $influxModel->writeData($activity, $ipAddress, $userId, $username, null, null);
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message'=> $e->getMessage(),
                'data'   => ""
            ));
        }
    }
}
