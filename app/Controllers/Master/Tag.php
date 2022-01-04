<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\TagModel;
use App\Models\AssetTagModel;
use App\Models\Influx\LogModel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use DateTime;
use Exception;

class Tag extends BaseController
{
    public function index()
    {
        if (!checkRoleList("MASTER.TAG.VIEW")) {
            return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
        }

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
        $request = \Config\Services::request();

        if (!checkRoleList("MASTER.TAG.VIEW")) {
            echo json_encode(array(
                "draw" => $request->getPost('draw'),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                'status' => 403,
                'message' => "You don't have access to this page"
            ));
        }

        $table = "tblm_tag";
        $column_order = array('tagId', 'tagName', 'description', 'createdAt');
        $column_search = array('tagId', 'tagName', 'description', 'createdAt');
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

    public function add()
    {
        if (!checkRoleList("MASTER.TAG.ADD")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }
        if (!checkLimitTag()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your tag has reached the limit"]);
		}

        $model = new TagModel();
        $influxModel    = new LogModel();

        $activity       = 'Add tag';

        $data = $this->request->getJSON();

        try {
            $dt = array(
                'tagId' => $data->tagId,
                'userId' => $this->session->get("adminId"),
                'tagName' => $data->tagName,
                'description' => $data->description
            );
            $model->insert($dt);

            $dataInflux = $dt;
            sendLog($activity, null, json_encode($dataInflux));

            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success add data",
                'data' => $dt
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => ""
            ]);
        }
    }

    public function edit()
    {
        if (!checkRoleList("MASTER.TAG.VIEW")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $model = new TagModel();

        $json = $this->request->getJSON();
        $tagId = $json->tagId;

        try {
            $tag = $model->where('tagId', $tagId)->get()->getResultArray();
            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success get data",
                'data' => $tag
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => ""
            ]);
        }
    }

    public function update()
    {
        if (!checkRoleList("MASTER.TAG.UPDATE")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $model = new TagModel();
        $influxModel    = new LogModel();

        $activity       = 'Update tag';

        $json = $this->request->getJSON();
        $tagId = $json->tagId;

        $data_before = $model->getById($tagId);
        try {
            $data = array(
                'tagName' => $json->tagName,
                'userId' => $this->session->get("adminId"),
                'description' => $json->description,
            );
            $model->update($tagId, $data);
            
            $data_after = $model->getById($tagId);
            $dataInflux = [
                'data_before' => $data_before,
                'data_after' => $data_after
            ];
            sendLog($activity, null, json_encode($dataInflux));

            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success updated data",
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => ""
            ]);
        }
    }

    public function deleteTag()
    {
        if (!checkRoleList("MASTER.TAG.DELETE")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $modelAssetTag  = new AssetTagModel();
        $modelTag       = new TagModel();
        $influxModel    = new LogModel();

        $activity       = 'Delete tag';

        $json = $this->request->getJSON();
        $tagId = $json->tagId;

        $data_deleted = $modelTag->getById($tagId);

        try {
            $modelAssetTag->deleteByIdTag($tagId);
            $modelTag->delete($tagId);

            $dataInflux = $data_deleted;
            sendLog($activity, null, json_encode($dataInflux));

            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success deleted data",
                'data' => $json
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => ""
            ]);
        }
    }

    public function download()
    {
        if (!checkRoleList("MASTER.TAG.IMPORT")) {
            return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
        }
        $influxModel    = new LogModel();

        $activity       = 'Download template tag';
        $ipAddress      = $this->request->getIPAddress();
        $username       = $this->session->get('name');
        $userId         = $this->session->get('adminId');

        try {
            sendLog($activity, null, null);
            return $this->response->download($_SERVER['DOCUMENT_ROOT'] . env('baseDir') . 'download/tag.xlsx', null);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => ""
            ]);
        }
    }
    public function uploadFile()
    {
        if (!checkRoleList("MASTER.TAG.IMPORT")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $file = $this->request->getFile('fileImportTag');
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
                                'tagName' => $row->getCellAtIndex(1)->getValue(),
                                'description' => $row->getCellAtIndex(2)->getValue(),
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

    public function insertTag()
    {
        if (!checkRoleList("MASTER.TAG.IMPORT")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }
        if (!checkLimitTag()) {
			return View('errors/customError', ['errorCode' => 400, 'errorMessage' => "Sorry, Your tag has reached the limit"]);
		}

        $tagModel = new TagModel();

        $activity       = 'Import tag';

        $json = $this->request->getJSON();
        $dataTag = $json->dataTag;
        $length = count($dataTag);

        try {
            for ($i = 0; $i < $length; $i++) {
                $data = [
                    'tagId' => null,
                    'userId' => $this->session->get("adminId"),
                    'tagName'   => $dataTag[$i]->tagName,
                    'description'   => $dataTag[$i]->description,
                ];
                $tagModel->insert($data);
            }
            sendLog($activity, null, json_encode($dataTag));
            return $this->response->setJSON([
                'status' => 200,
                'message' => "Success import data",
                'data' => $json
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => ""
            ]);
        }
    }

    public function exportExcel()
    {
        $tagModel = new TagModel();

        $activity       = 'Export tag';

        try {
            $writer = WriterEntityFactory::createXLSXWriter();
            $userId = $this->session->get('adminId');
            $data = $tagModel->where('userId', $userId)->findAll();
    
            $writer->setShouldUseInlineStrings(true);
            $header = ["No", "Tag", "Description"];
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
                    $arr = [$key + 1, $value['tagName'], $value['description']];
                    array_push($dataArr, $arr);
                }
            }
            $fileName = "Tag - " . date("d M Y") . '.xlsx';
            $writer->openToBrowser($fileName);
    
            $rowFromValues = WriterEntityFactory::createRowFromArray($header, $styleHeader);
            $writer->addRow($rowFromValues);
            foreach ($dataArr as $key => $value) {
                $rowFromValues = WriterEntityFactory::createRowFromArray($value, $styleBody);
                $writer->addRow($rowFromValues);
            }
            $writer->close();
            sendLog($activity, null, json_encode($data));
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => ""
            ]);
        }
    }
}
