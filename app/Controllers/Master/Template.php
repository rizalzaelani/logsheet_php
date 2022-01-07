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

            $file->move('../public/img/industrial/', $fileName);
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

    public function updateCategory()
    {
        $categoryModel = new CategoryModel();
        $post = $this->request->getPost('category');
        $category = json_decode($post, true);
        $image = $this->request->getFile('image');

        try {
            if (!empty($image)) {
                $dirPath = 'img/industrial/';
                $fileName = $category['categoryName'] . '_' . uniqid() . '.png';
                $image->move('../public/img/industrial/', $fileName);
                $path = base_url() . "/" . $dirPath . $fileName;

                $strPath = str_replace(base_url() . '/', "", $category['image']);
                unlink($strPath);
                $category['image'] = $path;
            }
            $data = [
                'categoryName'  => $category['categoryName'],
                'description'    => $category['description'],
                'image'         => $category['image']
            ];

            $categoryModel->update($category['categoryIndustryId'], $data);

            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Success update data',
                'data' => $category
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => $category
            ));
        }
    }

    public function datatableTemplate($categoryId)
    {
        $request = \Config\Services::request();

        $table = "vw_template";
        $column_order = array('templateId', 'name', 'path', 'descTemplate', 'categoryIndustryId', 'categoryName', 'image', 'descCategory', 'createdAt', 'updatedAt', 'deletedAt');
        $column_search = array('templateId', 'name', 'path', 'descTemplate', 'categoryIndustryId', 'categoryName', 'image', 'descCategory', 'createdAt', 'updatedAt', 'deletedAt');
        $order = array('createdAt' => 'desc');
        $DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
        $where = [
            'categoryIndustryId' => $categoryId,
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

    public function addTemplate()
    {
        $templateModel  = new TemplateModel();
        $categoryId     = $this->request->getPost('categoryId');
        $templateName   = $this->request->getPost('templateName');
        $descTemplate   = $this->request->getPost('descTemplate');
        $file           = $this->request->getFile('fileTemplate');

        if (empty($templateName) || empty($file)) {
            return $this->response->setJSON(array(
                'status'    => 500,
                'message'   => 'Bad request!',
                'data'      => []
            ));
        }

        try {
            $dirPath    = 'assets/Sample/';
            $fileName   = $templateName . '_' . uniqid() . '.xls';
            $file->move('../public/assets/sample/', $fileName);
            $path = base_url() . "/" . $dirPath . $fileName;

            $data = [
                'templateId'            => uuidv4(),
                'categoryIndustryId'    => $categoryId,
                'name'                  => $templateName,
                'description'           => $descTemplate,
                'path'                  => $path
            ];
            $templateModel->insert($data);

            return $this->response->setJSON(array(
                'status'    => 200,
                'message'   => 'Success add template',
                'data'      => []
            ));
        } catch (Exception $e) {
            return $this->response->setJSON((array(
                'status'    => $e->getCode(),
                'message'   => $e->getMessage(),
                'data'      => []
            )));
        }
    }

    public function editTemplate()
    {
        $templateModel = new TemplateModel();

        $json = $this->request->getJSON();
        $templateId = $json->templateId;

        try {
            $template = $templateModel->getAll(['templateId' => $templateId]);
            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Success get data',
                'data' => $template
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => []
            ));
        }
    }

    public function updateTemplate()
    {
        $templateModel = new TemplateModel();

        $post = $this->request->getPost('template');
        $template = json_decode($post);
        $templateId = $template->templateId;
        $file = $this->request->getFile('file');

        if (empty($template->name)) {
            return $this->response->setJSON(array(
                'status'    => 500,
                'message'   => 'Bad request!',
                'data'      => []
            ));
        }

        try {
            $path = "";
            if (!empty($file)) {
                $dirPath    = 'assets/Sample/';
                $fileName   = $template->name . '_' . uniqid() . '.xls';
                $file->move('../public/assets/sample/', $fileName);
                $path = base_url() . "/" . $dirPath . $fileName;

                $strPath = str_replace(base_url() . '/', "", $template->path);
                unlink($strPath);
            }

            $data = [
                'name'          => $template->name,
                'description'   => $template->descTemplate
            ];

            if (!empty($file)) {
                $data['path'] = $path;
            }

            $templateModel->update($templateId, $data);
            return $this->response->setJSON(array(
                'status'    => 200,
                'message'   => 'Success update data',
                'data'      => $data
            ));
        } catch (Exception $e) {
            return $this->response->setJSON(array(
                'status'    => $e->getCode(),
                'message'   => $e->getMessage(),
                'data'      => []
            ));
        }
    }

    public function deleteTemplate()
    {
        $templateModel = new TemplateModel();

        $json = $this->request->getJSON();
        $templateId = $json->templateId;

        if (empty($templateId)) {
            $this->response->setJSON(array(
                'status' => 500,
                'message' => 'Bad request!'
            ));
        }

        try {
            $template = $templateModel->getAll(['templateId' => $templateId]);
            if (!empty($template)) {
                $dt = $template[0];
                $strPath = str_replace(base_url() . '/', "", $dt['path']);
                unlink($strPath);

                $templateModel->delete($templateId);
            }

            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Success delete data',
                'data' => $dt
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
