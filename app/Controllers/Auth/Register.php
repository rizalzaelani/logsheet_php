<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\USMAN\AppsModel;
use Exception;
use HTTP_Request2;
use HTTP_Request2_Exception;

class Register extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Register Page | Logsheet Digital',
            'subtitle' => 'Logsheet Digital'
        );

        return $this->template->render('Auth/register', $data);
    }

    public function doRegister()
    {
        $isValid = $this->validate([
            'fullname' => 'required',
            'noTelp' => 'required',
            'email' => 'required',
            'password' => 'required',
            'company' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'country' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJSON([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }
        

        $param["name"] = $this->request->getVar('appName');
        $param["code"] = str_replace(" ", "-", strtolower($param["name"]));
        $param["description"] = "-";

        $param['email'] = $this->request->getVar('email');
        $param['password'] = $this->request->getVar('password');
        $param['confirm_password'] = $this->request->getVar('password');
        $param['app_url'] = base_url("/");
        $param['app_group'] = "logsheet";

        $param['parameter[fullname]'] = $this->request->getVar('fullname');
        $param['parameter[noTelp]'] = $this->request->getVar('noTelp');
        $param['parameter[company]'] = $this->request->getVar('company');
        $param['parameter[city]'] = $this->request->getVar('city');
        $param['parameter[postalCode]'] = $this->request->getVar('postalCode');
        $param['parameter[country]'] = $this->request->getVar('country');
        
        $param['group'] = "Superadmin";
        $param['role'] = getenv("ROLELIST");
        $param['roleGroup[Superadmin]'] = getenv("ROLELIST");

        try {
            $appModel = new AppsModel();
            $dataRes = $appModel->createApps($param);
            
            $data = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($data->message) ? 400 : 500,
                    'message' => $data->message ?? $dataRes['message'],
                    'data' => $data
                ), isset($data->message) ? 400 : 500);
            } else {
                return $this->response->setJSON([
                    'status' => 200,
                    'error' => true,
                    'message' => "Success Create App",
                    'data' => $data
                ], 200);
            }
        } catch (Exception $e){
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }
}
