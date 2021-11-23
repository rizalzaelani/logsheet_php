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
            'street' => 'required',
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
        

        $data["name"] = $this->request->getVar('appName');
        $data["code"] = str_replace(" ", "-", strtolower($data["name"]));
        $data["description"] = "-";

        $data['email'] = $this->request->getVar('email');
        $data['password'] = $this->request->getVar('password');
        $data['confirm_password'] = $this->request->getVar('password');
        $data['app_url'] = base_url("/");
        $data['app_group'] = "logsheet";

        $data['parameter[fullname]'] = $this->request->getVar('fullname');
        $data['parameter[noTelp]'] = $this->request->getVar('noTelp');
        $data['parameter[street]'] = $this->request->getVar('street');
        $data['parameter[city]'] = $this->request->getVar('city');
        $data['parameter[postalCode]'] = $this->request->getVar('postalCode');
        $data['parameter[country]'] = $this->request->getVar('country');

        try {
            $appModel = new AppsModel();
            $dataRes = $appModel->createApps($data);
            
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
