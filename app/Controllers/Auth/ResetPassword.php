<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\USMAN\UserModel;
use Exception;

class ResetPassword extends BaseController
{
    public function index($token = "")
    {
        $data = array(
            'title' => 'Reset Password | Logsheet Digital',
            'subtitle' => 'Logsheet Digital',
            'token' => $token
        );

        return $this->template->render('Auth/resetPassword', $data);
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

    public function doResetPassword()
    {
        $isValid = $this->validate([
            'token' => 'required',
            'password' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJSON([
                'status' => 400,
                'error' => true,
                'message' => "Invalid Form",
                'data' => $this->validator->getErrors()
            ], 400);
        }

        try {
            $userModel = new UserModel();
            $dataRes = $userModel->resetPassword($this->request->getVar("token"), ['password' => $this->request->getVar("password")]);

            $resData = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($resData->message) ? 400 : 500,
                    'message' => $resData->message ?? $dataRes['message'],
                    'data' => $resData
                ), isset($resData->message) ? 400 : 500);
            } else {
                return $this->response->setJSON([
                    'status' => 200,
                    'message' => "Your Password is changed, Please Try it on Login Page",
                    'data' => $resData
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

    public function sendMailForgotPassword()
    {
        $isValid = $this->validate([
            'email' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJSON([
                'status' => 400,
                'error' => true,
                'message' => "Invalid Form",
                'data' => $this->validator->getErrors()
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
                $linkReset = site_url("resetPassword/") . $resData->data->token;
                $message = file_get_contents(base_url() . "/assets/Mail/forgotPassword.txt");

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
                    'data' => $resData
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
}
