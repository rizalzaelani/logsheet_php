<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\USMAN\AppsModel;
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

        $data['fullname'] = $this->request->getVar('fullname');
        $data['noTelp'] = $this->request->getVar('noTelp');
        $data['email'] = $this->request->getVar('email');
        $data['password'] = $this->request->getVar('password');
        $data['street'] = $this->request->getVar('street');
        $data['city'] = $this->request->getVar('city');
        $data['postalCode'] = $this->request->getVar('postalCode');
        $data['country'] = $this->request->getVar('country');

        $data["name"] = $this->request->getVar('country');
        $data["code"] = str_replace(" ", "-", strtolower($data["name"]));
        $data["description"] = "";

        $appModel = new AppsModel();
        $res = $appModel->createApps($data);

        return $this->response->setJSON([
            'status' => 200,
            'error' => true,
            'message' => "Success Create App",
            'data' => $res
        ], 200);
    }
}
