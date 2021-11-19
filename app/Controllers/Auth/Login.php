<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\USMAN\UserModel;
use Exception;

class Login extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();
		if($session->has('userId')){
            return redirect()->to("Dashboard");
        }
        
        $data = array(
            'title' => 'Login Page | Logsheet Digital',
            'subtitle' => 'Logsheet Digital'
        );

        return $this->template->render('Auth/login', $data);
    }
    public function auth()
    {
        $session = \Config\Services::session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $captcha = $this->request->getPost('g-recaptcha-response');
        $appCode = 'logsheet01';
        $params = [
            'email' => $email,
            'password' => $password,
            'appCode' => $appCode
        ];

        if(!$captcha){
            return $this->response->setJSON(array(
                'status'    =>  400,
                'message'   =>  'Complete the captcha first'
            ), 400);
        } else {
            try {
                $secret_key = env('secret_key');
                $url = env('captcha_url') . '?secret=' . urlencode($secret_key) .  '&response=' . urlencode($captcha);
                $response_captcha = file_get_contents($url);
                $response_captcha_key = json_decode($response_captcha, true);

                if($response_captcha_key['success']){
                    $userModel = new UserModel();
                    $dataRes = $userModel->clientAuth($params);

                    // return $this->response->setJSON(array(
                    //     'status'    =>  400,
                    //     'message'   =>  'Test Incorect Password',
                    //     'data'      => $dataRes
                    // ), 400);

                    $data = json_decode($dataRes['data']);
                    if ($dataRes['error']) {
                        return $this->response->setJSON(array(
                            'status' => isset($data->message) ? 400 : 500,
                            'message' => $data->message ?? $dataRes['message']
                        ), isset($data->message) ? 400 : 500);
                    } else {
                        $dataArr = $data->data;
                        unset($dataArr->token);
                        unset($dataArr->parameter);
                        $session->set((array) $dataArr);
                        return $this->response->setJSON(array(
                            'status' => 200,
                            'message' => 'Success Login'
                        ), 200);
                    }
                } else {
                    return $this->response->setJSON(array(
                        'status'    =>  400,
                        'message'   =>  'Too many attemps, try again later'
                    ), 400);
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

    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to(base_url());
    }
}
