<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\ApplicationSettingModel;
use App\Models\TagLocationModel;
use App\Models\TagModel;
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

    public function forgotPassword()
    {
        $session = \Config\Services::session();
        if ($session->has('userId')) {
            return redirect()->to("Dashboard");
        }
        $data = array(
            'title' => 'Login Page | Logsheet Digital',
            'subtitle' => 'Logsheet Digital',
        );

        return $this->template->render('Auth/forgotPassword', $data);
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
                        if (empty($appSetting)) {
                            $this->response->setCookie('appName', "", 60 * 60 * 24 * 365, '', '/logsheet');
                            $this->response->setCookie('appLogoLight', "", 60 * 60 * 24 * 365, '', '/logsheet');
                            $this->response->setCookie('appLogoDark', "", 60 * 60 * 24 * 365, '', '/logsheet');
                            $this->response->setCookie('appLogoIcon', "", 60 * 60 * 24 * 365, '', '/logsheet');
                        } else {
                            $this->response->setCookie('appName', $appSetting["appName"], 60 * 60 * 24 * 365, '', '/logsheet');
                            $this->response->setCookie('appLogoLight', $appSetting["appLogoLight"], 60 * 60 * 24 * 365, '', '/logsheet');
                            $this->response->setCookie('appLogoDark', $appSetting["appLogoDark"], 60 * 60 * 24 * 365, '', '/logsheet');
                            $this->response->setCookie('appLogoIcon', $appSetting["appLogoIcon"], 60 * 60 * 24 * 365, '', '/logsheet');
                        }
                        $tagModel = new TagModel();
                        $tagLocModel = new TagLocationModel();

                        $tagData = $tagModel->getAll(["userId" => $dataArr->adminId]);
                        $tagLocData = $tagLocModel->getAll(["userId" => $dataArr->adminId]);

                        $session->set((array) $dataArr);
                        $this->response->setCookie('clientToken', "active", 3600);

                        return $this->response->setJSON(array(
                            'status' => 200,
                            'message' => 'Success Login',
                            'data' => [
                                'tagData' => $tagData,
                                'tagLocationData' => $tagLocData
                            ]
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

    public function sendMailForgotPassword()
    {
        $isValid = $this->validate([
            'email' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJSON([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        try {
            $userModel = new UserModel();
            $dataRes = $userModel->forgotPassword(['email' => $this->request->getVar("email")]);

            $resData = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($resData->message) ? 400 : 500,
                    'message' => $resData->message ?? $dataRes['message'],
                    'data' => $resData
                ), isset($resData->message) ? 400 : 500);
            } else {
                $linkReset = site_url("resetPassword") . "?secret=" . $resData->data->token . "&userId=" . $resData->data->userId . "&mail=" . $this->request->getVar("email");
                $message = file_get_contents(base_url()."/assets/Mail/forgotPassword.txt");
    
                $message = str_replace("{{linkBtnReset}}", $linkReset, $message);
    
                $email = \Config\Services::email();
    
                $email->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
                $email->setTo($this->request->getVar("email"));
                $email->setSubject('Reset your Logsheet Digital password');
                $email->setMessage($message);
                $email->setMailType("html");
    
                $email->send();
                // $email->printDebugger(['headers']);

                return $this->response->setJSON([
                    'status' => 200,
                    'message' => "Please check your mailbox",
                    'data' => $resData,
                    "message" => $message
                ], 200);
            }
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
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

    public function testMail()
    {
        try {
            $message = file_get_contents(base_url()."/assets/Mail/forgotPassword.txt");
            // $message = readfile('assets/Mail/forgotPassword.txt');

            // // str_replace("{{linkBtnReset}}",(site_url("forgotPassword") . "?secret=" . $resData->data->token), $message)

            // $email = \Config\Services::email();

            // $email->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
            // $email->setTo("barry.naz.zul@gmail.com");
            // $email->setSubject('Test Logsheet Mail');
            // $email->setMessage($message); //your message here
            // $email->setMailType("html");

            // // $email->setCC('another@emailHere'); //CC
            // // $email->setBCC('thirdEmail@emialHere'); // and BCC
            // // $filename = '/img/yourPhoto.jpg'; //you can use the App patch 
            // // $email->attach($filename);

            // $email->send();
            // $email->printDebugger(['headers']);

            echo "Success";
        } catch (Exception $e) {
            echo "<pre />";
            print_r($e);
        }
    }

    public function testTelegram()
    {
        try {
            $chatId = $this->request->getVar("chatId") ?? "586103052";
            $bot = new \TelegramBot\Api\BotApi(env('botTelegramToken'));

            $bot->sendMessage($chatId, "Hallo, Welcome to Logsheet Digital");

            echo "success";
        } catch (Exception $e) {
            echo "<pre />";
            print_r($e);
        }
    }
}
