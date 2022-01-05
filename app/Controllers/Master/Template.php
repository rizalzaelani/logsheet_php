<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\TemplateModel;
use Exception;

class Template extends BaseController
{
    public function index()
    {
        // if (!checkRoleList("MASTER.TAG.VIEW")) {
        //     return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
        // }

        $data = array(
            'title' => 'Template',
            'subtitle' => 'List Template',
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Template",
                "link"    => "Template"
            ],
        ];

        return $this->template->render('Master/Template/index', $data);
    }

    public function datatable()
    {
        $request = \Config\Services::request();

        $table = "tblm_categoryIndustry";
        $column_order = array('categoryIndustryId', 'categoryName', 'image', 'description', 'createdAt', 'updatedAt', 'deletedAt');
        $column_search = array('categoryIndustryId', 'categoryName', 'image', 'description', 'createdAt', 'updatedAt', 'deletedAt');
        $order = array('createdAt' => 'desc');
        $DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
        $where = [
            // 'userId' => $this->session->get("adminId"),
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

    public function detail($categoryId)
    {
        $templateModel = new TemplateModel();
        $categoryModel = new CategoryModel();
        $dataTemplate = $templateModel->getAll(['categoryIndustryId' => $categoryId]);
        $dataCategory = $categoryModel->getById($categoryId);

        $data = array(
            'title' => 'Detail Category',
        );

        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Template",
                "link"    => "Template"
            ],
            [
                "title"    => "Detail",
                "link"    => "detail"
            ],
        ];

        $data['template'] = $dataTemplate;
        $data['category'] = $dataCategory;

        return $this->template->render('Master/Template/detail', $data);
    }

    public function addCategory()
    {
        $categoryModel = new CategoryModel();

        $categoryName = $this->request->getPost('categoryName');
        $descCategory = $this->request->getPost('descCategory');
        $file = $this->request->getFile('image');

        if (empty($categoryName) || empty($descCategory) || empty($file)) {
            return $this->response->setJSON(array(
                'status' => 500,
                'message' => 'Bad request!'
            ));
        }

        try {
            $dirPath = 'img/industrial/';
            $fileName = $categoryName . '_' . uniqid() . '.png';
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $dirPath . $fileName, $file);
            $image = base_url() . "/" . $dirPath . $fileName;

            $data = [
                'categoryIndustryId' => uuidv4(),
                'categoryName'  => $categoryName,
                'description'   => $descCategory,
                'image'         => $image
            ];

            $categoryModel->insert($data);

            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Success add category',
                'data' => []
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ));
        }
    }
}
