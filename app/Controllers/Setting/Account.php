<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\Influx\LogModel;
use App\Models\USMAN\UserModel;
use Exception;

class Account extends BaseController
{
    public function index()
    {
        $logModel = new LogModel();

        $activity = 'Sign in to application';
        $userId = $this->session->get('adminId');
        $dataLogin = $logModel->getLogLogin($activity, $userId);

        if (!checkRoleList("APPLICATION.VIEW")) {
            return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
        }

        $userId = $this->session->get("userId");
        $data = array(
            'title' => 'User Account',
            'subtitle' => 'Detail information about user'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Account",
                "link"    => "Account"
            ],
        ];

        $parameterUser = json_decode($this->session->get("parameter"), true);

        $data["userData"] = array(
            "email" => $this->session->get("email"),
            "role" => $this->session->get("group"),
            "fullname" => $parameterUser['fullname'],
            "company" => $parameterUser['company'],
            "city" => $parameterUser['city'],
            "country" => $parameterUser['country'],
            "postalCode" => $parameterUser['postalCode'],
            "noTelp" => $parameterUser['noTelp'],
            "tag" => $parameterUser['tag'],
            "tagLocation" => $parameterUser['tagLocation'],
        );
        $data['logLogin'] = $dataLogin;

        return $this->template->render('Setting/Account/index.php', $data);
    }

    public function changePassword()
    {
        $isValid = $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required'
        ]);

        if (!$isValid) {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid",
                'data' => $this->validator->getErrors()
            ], 400);
        }

        $currentPassword = $this->request->getVar("currentPassword");
        $newPassword = $this->request->getVar("newPassword");

        $userModel = new UserModel();

        try {
            $clientToken = get_cookie("clientToken");
            if (!isset($clientToken) || $clientToken == null) {
                $resRT = $userModel->refreshToken();
                if ($resRT['error']) {
                    return $this->response->setJSON(array(
                        'status' => isset($resRT['data']->message) ? 400 : 500,
                        'message' => $resRT['data']->message ?? $resRT['message'],
                        'data' => $resRT['data']
                    ), isset($resRT['data']->message) ? 400 : 500);
                }
            }

            $param["current_password"] = $currentPassword;
            $param["password"] = $newPassword;
            $param["confirm_password"] = $newPassword;
            $param["userId"] = $this->session->get("userId");

            $dataRes = $userModel->changePassword($param);

            $dataResData = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($dataResData->message) ? 400 : 500,
                    'message' => $dataResData->message ?? $dataRes['message'],
                    'data' => $dataResData,
                    'token' => $this->session->get("token"),
                    'userId' => $this->session->get("userId")
                ), isset($dataResData->message) ? 400 : 500);
            } else {
                $activity = 'Change password user';
                sendLog($activity, null, json_encode($param));
                return $this->response->setJSON([
                    'status' => 200,
                    'error' => true,
                    'message' => "Success change password user",
                    'data' => $dataResData->data ?? [],
                    'dataParam' => $param
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
