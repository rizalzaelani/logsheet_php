<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\TagModel;
use App\Models\AssetTagModel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Tag extends BaseController
{
    public function index()
    {
        if(!checkRoleList("MASTER.TAG.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
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

        if(!checkRoleList("MASTER.TAG.VIEW")){
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
        if(!checkRoleList("MASTER.TAG.ADD")){
			return $this->response->setJSON([
				'status' => 403,
                'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
        }

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
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'All fields cannot be empty.'));
        }
        die();
    }

    public function edit()
    {
        if(!checkRoleList("MASTER.TAG.VIEW")){
			return $this->response->setJSON([
				'status' => 403,
                'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
        }

        $model = new TagModel();
        $json = $this->request->getJSON();
        $tagId = $json->tagId;
        $tag = $model->where('tagId', $tagId)->get()->getResultArray();
        echo json_encode(array('status' => 'success', 'data' => $tag));
        die();
    }

    public function update()
    {
        if(!checkRoleList("MASTER.TAG.UPDATE")){
			return $this->response->setJSON([
				'status' => 403,
                'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
        }

        $model = new TagModel();
        $json = $this->request->getJSON();
        $tagId = $json->tagId;
        if ($json->tagName != '' && $json->description != '') {
            $data = array(
                'tagName' => $json->tagName,
                'description' => $json->description,
            );
            $model->update($tagId, $data);
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully update data.', 'data' => $data));
        }else{
            echo json_encode(array('status' => 'failed', 'message' => 'All fields cannot be empty.', 'data' => $json));
        }
        die();
    }

    public function deleteTag()
    {
        if(!checkRoleList("MASTER.TAG.DELETE")){
			return $this->response->setJSON([
				'status' => 403,
                'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
        }

        $modelAssetTag = new AssetTagModel();
        $modelTag = new TagModel();
        $json = $this->request->getJSON();
        $tagId = $json->tagId;
        if ($tagId != '') {
            $modelAssetTag->deleteByIdTag($tagId);
            $modelTag->delete($tagId);
            echo json_encode(array('status' => 'success', 'message' => 'You have successfully deleted data.', 'data' => $json));
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'Bad Request!', 'data' => $json));
        }
        die();
    }

    public function download()
    {
        if(!checkRoleList("MASTER.TAG.IMPORT.SAMPLE")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        }

        return $this->response->download('../public/download/tag.xlsx', null);
    }
    public function uploadFile()
    {
        if(!checkRoleList("MASTER.TAG.IMPORT")){
			return $this->response->setJSON([
				'status' => 403,
                'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
        }

        $file = $this->request->getFile('fileImportTag');
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

    public function insertTag()
    {
        if(!checkRoleList("MASTER.TAG.IMPORT")){
			return $this->response->setJSON([
				'status' => 403,
                'message' => "Sorry, You don't have access",
				'data' => []
			], 403);
        }

        $tagModel = new TagModel();
        $json = $this->request->getJSON();
        $dataTag = $json->dataTag;
        $length = count($dataTag);
        for ($i = 0; $i < $length; $i++) {
            $uuid = $this->gen_uuid();
            $data = [
                'tagName'   => $dataTag[$i]->tagName,
                'description'   => $dataTag[$i]->description,
            ];
            $tagModel->insert($data);
        }
        echo json_encode(array('status' => 'success', 'message' => '', 'data' => $json));
        die();
    }
}
