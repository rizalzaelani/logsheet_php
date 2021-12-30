<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\ApplicationSettingModel;
use App\Models\Influx\LogActivityModel;
use App\Models\Influx\LogModel;
use App\Models\TagLocationModel;
use App\Models\TagModel;
use App\Models\USMAN\UserModel;
use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;

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
            'platform' => env('platform')
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
                $user_agent = $this->request->getUserAgent();

                if ($response_captcha_key['success']) {
                    $userModel = new UserModel();
                    $logModel = new LogModel();
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

                        if ($dataArr->email_valid == 0) {
                            return $this->response->setJSON(array(
                                'status' => 400,
                                'message' => 'Email is Not Verified',
                                'email' => $email,
                                'userId' => $dataArr->userId,
                                'data' => []
                            ), 200);
                        } else {
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

                            $activity       = 'Sign in to application';
                            $ipAddress      = $this->request->getIPAddress();
                            $username       = $this->session->get('name');
                            $userId         = $this->session->get('adminId');
                            $userAgent      = $this->request->getUserAgent();
                            $browser        = $userAgent->getBrowser() . ' ' . $userAgent->getVersion();
                            $platform       = $userAgent->getPlatform();
                            $isMobile       = $userAgent->isMobile();
                            $arr            = [
                                'browser' => $browser,
                                'platform' => $platform,
                                'isMobile' => $isMobile
                            ];

                            sendLog($activity, null, json_encode($arr));

                            return $this->response->setJSON(array(
                                'status' => 200,
                                'message' => 'Success Login',
                                'data' => [
                                    'tagData' => $tagData,
                                    'tagLocationData' => $tagLocData
                                ]
                            ), 200);
                        }
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

    public function sendMailVerification()
    {
        $isValid = $this->validate([
            'email' => 'required',
            'userId' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJSON([
                'status' => 400,
                'error' => true,
                'message' => "Invalid Data",
                'data' => $this->validator->getErrors()
            ], 400);
        }

        $email = $this->request->getVar("email");
        $userId = $this->request->getVar("userId");

        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+10 years')->getTimestamp();
        $serverName = getenv("DOMAIN_NAME");
        $jwtPayload = [
            'iss'  => $serverName,                       // Issuer
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'nbf'  => $issuedAt->getTimestamp(),         // Not before
            'exp'  => $expire,                           // Expire
            'email' => $email,
            'data' => [
                'email' => $email,
                'userId' => $userId,
            ]
        ];

        $jwt = JWT::encode($jwtPayload, getenv("JWT_SECRET_KEY_MAIL_VERIFY"));

        $linkReset = site_url("verifyMail/") . $jwt;
        $message = file_get_contents(base_url() . "/assets/Mail/verifyMail.txt");

        $message = str_replace("{{linkBtn}}", $linkReset, $message);
        $message = str_replace("{{emailAddress}}", $email, $message);

        $mailService = \Config\Services::email();

        $mailService->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
        $mailService->setTo($email);
        $mailService->setSubject('Verify your Logsheet Digital Account');
        $mailService->setMessage($message);
        $mailService->setMailType("html");

        $mailService->send();

        return $this->response->setJSON([
            'status' => 200,
            'message' => "Success Send Verification email",
            'data' => []
        ], 200);
    }

    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        $this->response->deleteCookie('clientToken');
        delete_cookie("clientToken");

        $userAgent  = $this->request->getUserAgent();
        $browser    = $userAgent->getBrowser() . ' ' . $userAgent->getVersion();
        $platform   = $userAgent->getPlatform();
        $isMobile   = $userAgent->isMobile();
        $data = [
            'browser'   => $browser,
            'platform'  => $platform,
            'isMobile'  => $isMobile
        ];
        sendLog('Log out application', null, json_encode($data));

        return redirect()->to(base_url());
    }

    public function testMail()
    {
        try {
            // $logActModel = new LogActivityModel();
            // $logActModel->writeLog();

            echo "Success - " . microtime(true);
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
