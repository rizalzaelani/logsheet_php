<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\ApplicationSettingModel;
use App\Models\USMAN\UserModel;
use Exception;

class Login extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();
        if ($session->has('userId')) {
            return redirect()->to("Dashboard");
        }
        $data = array(
            'title' => 'Login Page | Logsheet Digital',
            'subtitle' => 'Logsheet Digital',
        );

        return $this->template->render('Auth/login', $data);
    }
    
    public function auth()
    {
        $session = \Config\Services::session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $captcha = $this->request->getPost('g-recaptcha-response');
        $params = [
            'email' => $email,
            'password' => $password,
        ];

        if (!$captcha) {
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

                if ($response_captcha_key['success']) {
                    $userModel = new UserModel();
                    $dataRes = $userModel->clientAuth($params);

                    $data = $dataRes['data'];
                    if ($dataRes['error']) {
                        return $this->response->setJSON(array(
                            'status' => isset($data->message) ? 400 : 500,
                            'message' => $data->message ?? $dataRes['message']
                        ), isset($data->message) ? 400 : 500);
                    } else {
                        $dataArr = $data->data;
                        $dataArr->password = $password;

                        $appSettingModel = new ApplicationSettingModel();
                        $appSetting = $appSettingModel->where("userId", $dataArr->adminId)->get()->getRowArray();
                        if(empty($appSetting)){
                            $this->response->setCookie('appName', "", 60 * 60 * 24 * 365);
                            $this->response->setCookie('appLogoLight', "", 60 * 60 * 24 * 365);
                            $this->response->setCookie('appLogoDark', "", 60 * 60 * 24 * 365);
                            $this->response->setCookie('appLogoIcon', "", 60 * 60 * 24 * 365);
                        } else {
                            $this->response->setCookie('appName', $appSetting["appName"], 60 * 60 * 24 * 365);
                            $this->response->setCookie('appLogoLight', $appSetting["appLogoLight"], 60 * 60 * 24 * 365);
                            $this->response->setCookie('appLogoDark', $appSetting["appLogoDark"], 60 * 60 * 24 * 365);
                            $this->response->setCookie('appLogoIcon', $appSetting["appLogoIcon"], 60 * 60 * 24 * 365);
                        }

                        $session->set((array) $dataArr);
                        $this->response->setCookie('clientToken', "active", 3600);
                        
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
            } catch (Exception $e) {
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
		$this->response->deleteCookie('clientToken');
        delete_cookie("clientToken");

        return redirect()->to(base_url());
    }
}
