<?php

namespace App\Controllers\Industrial;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use Exception;

class Industrial extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();

        $category = $categoryModel->findAll();

        $data = array(
            'title' => 'Select Industrial | Logsheet Digital',
            'subtitle' => 'Logsheet Digital',
        );
        $data['category'] = $category;

        return $this->template->render('Industrial/index', $data);
    }

    public function doGenerate()
    {
        $selected = $this->request->getVar('selected');

        try {
            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Success generate template',
                'data' => []
            ));
        } catch (Exception $e) {
            $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ));
        }
    }
}
