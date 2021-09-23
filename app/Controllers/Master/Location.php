<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AssetTaggingModel;
use App\Models\AssetTagLocationModel;
use App\Models\TagModel;
use App\Models\LocationModel;
use App\Models\StatusAssetModel;
use App\Models\AssetTagModel;
use App\Models\ParameterModel;
use CodeIgniter\API\ResponseTrait;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\XLSX\Reader;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Row;

use Dompdf\Dompdf;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

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
        $model = new LocationModel();
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
        $model = new LocationModel();
        $data = array(
            'title' => 'Add Tag Location',
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
            [
                "title"    => "Add",
                "link"    => "add"
            ],
        ];
        return $this->template->render('Master/Location/add', $data);
    }

    public function addTagLocation()
    {
        $model = new LocationModel();
        $json = $this->request->getJSON();
        if ($json->tagLocationName != '') {
            $data = array(
                'tagLocationName' => $json->tagLocationName,
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
        $model = new LocationModel();
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
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully updated data.', 'data' => $data));
        }
        die();
    }

    public function delete()
    {
        $locationModel = new LocationModel();
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
        return $this->response->download('../public/download/location.xlsx', null);
    }
    public function uploadFile()
    {
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

    function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
    
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
    
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public function insertLocation()
    {
        $tagLocationModel = new LocationModel();
        $json = $this->request->getJSON();
        $dataLocation = $json->dataLocation;
        $length = count($dataLocation);
        for ($i = 0; $i < $length; $i++) {
            $uuid = $this->gen_uuid();
            $data = [
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
