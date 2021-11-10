<?php

namespace App\Controllers\Login;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use PhpParser\Node\Stmt\Echo_;

class Login extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Login Page | Logsheet Digital',
            'subtitle' => 'Logsheet Digital'
        );

        return $this->template->render('Login/index', $data);
    }
    public function auth()
    {
        $session = \Config\Services::session();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $appCode = 'logsheet01';
        $params = [
            'email' => $email,
            'password' => $password,
            'appCode' => $appCode
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://139.180.218.82/api/auth/auth_client');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:multipart/form-data'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Requested-With:XMLHTTPRequest'));
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result);
        $status = $data->status;
        if (!$status) {
            $message = $data->message;
            return $this->response->setJSON(array(
                'status' => 400,
                'message' => $message
            ));
        }else{
            $dataArr = $data->data;
            unset($dataArr->token);
            $session->set($dataArr);
            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Success Login'
            ));
        }
        die();
    }
}
