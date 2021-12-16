<?php

namespace App\Controllers\UserRole;

use App\Controllers\BaseController;
use App\Models\TagLocationModel;
use App\Models\TagModel;
use App\Models\USMAN\RoleGroupModel;
use App\Models\USMAN\UserModel;
use Exception;

class User extends BaseController
{
    public function index()
    {
        if (!checkRoleList("USER.VIEW")) {
            return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
        }

        $data = array(
            'title' => 'User Logsheet Digital',
            'subtitle' => 'All User Logsheet',
            'groupData' => []
        );

        $tagModel = new TagModel();
        $tagLocationModel = new TagLocationModel();

        $data["tagData"] = $tagModel->getAll(["userId" => $this->session->get("adminId")]);
        $data["tagLocationData"] = $tagLocationModel->getAll(["userId" => $this->session->get("adminId")]);

        try {
            $userModel = new UserModel();
            $roleGroupModel = new RoleGroupModel();

            $clientToken = get_cookie("clientToken");
            if (!isset($clientToken) || $clientToken == null) {
                $resRT = $userModel->refreshToken();
                if ($resRT['error']) {
                    //
                }
            }
            $dataRes = $roleGroupModel->groupList();

            $dataResData = $dataRes['data'];
            if ($dataRes['error']) {
                //
            } else {
                $data["groupData"] = $dataResData->data;
            }
        } catch (Exception $e) {
            //
        }

        return $this->template->render('UserRole/User/index', $data);
    }

    public function userList()
    {
        if (!checkRoleList("USER.VIEW")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        try {
            $userModel = new UserModel();
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
            $dataRes = $userModel->userList();

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
                    'message' => "Success Get User List",
                    'data' => $data->data ?? []
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

    public function getUserById()
    {
        if (!checkRoleList("USER.VIEW")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        try {
            $userModel = new UserModel();
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

            $userId = $this->request->getGet("userId") ?? "";
            $dataRes = $userModel->userDetail($userId);

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
                    'message' => "Success Get User Detail",
                    'data' => $data->data ?? []
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

    public function saveUser()
    {
        $userId = $this->request->getVar("userId") ?? "";
        if (($userId == "" && !checkRoleList("USER.ADD")) || ($userId != "" && !checkRoleList("USER.MODIFY"))) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $isValid = $this->validate([
            'name' => 'required',
            'email' => 'required',
            'groupId' => 'required'
        ]);

        if (!$isValid) {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid",
                'data' => $this->validator->getErrors()
            ], 400);
        }

        $name = $this->request->getVar("name");
        $email = $this->request->getVar("email");
        $password = $this->request->getVar("password") ?? "";
        $tag = $this->request->getVar("tagId");
        $tagLocation = $this->request->getVar("tagLocationId");
        $groupId = $this->request->getVar("groupId");

        if ($userId == "" && $password == "") {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid",
                'data' => ["password" => "The password field is required."]
            ], 400);
        }

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

            $param["name"] = $name;
            $param["email"] = $email;
            $param["groupId"] = $groupId;
            $param["appId"] = $this->session->get("appId");

            $param["parameter[tag]"] = $tag;
            $param["parameter[tagLocation]"] = $tagLocation;

            if ($userId != "") {
                $param["userId"] = $userId;
                $param["password"] = $password;
                $param["confirm_password"] = $password;
            } else {
                if ($password != "") {
                    $param["password"] = $password;
                    $param["confirm_password"] = $password;
                }
            }

            $dataRes = $userModel->saveUser($param);

            $dataResData = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($dataResData->message) ? 400 : 500,
                    'message' => $dataResData->message ?? $dataRes['message'],
                    'data' => $dataResData
                ), isset($dataResData->message) ? 400 : 500);
            } else {
                return $this->response->setJSON([
                    'status' => 200,
                    'error' => true,
                    'message' => "Success " . ($groupId != "" ? "update" : "create") . " user",
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

    public function deleteUser()
    {
        if (!checkRoleList("USER.DELETE")) {
            return $this->response->setJSON([
                'status' => 403,
                'message' => "Sorry, You don't have access",
                'data' => []
            ], 403);
        }

        $isValid = $this->validate([
            'userId' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid",
                'data' => $this->validator->getErrors()
            ], 400);
        }

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

            $param["userId"] = $this->request->getVar("userId");
            $param["appId"] = $this->session->get("appId");
            $param["force"] = true;
            $dataRes = $userModel->deleteUser($param);

            $dataResData = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($dataResData->message) ? 400 : 500,
                    'message' => $dataResData->message ?? $dataRes['message'],
                    'data' => $dataResData
                ), isset($dataResData->message) ? 400 : 500);
            } else {
                return $this->response->setJSON([
                    'status' => 200,
                    'error' => true,
                    'message' => "Success Delete user",
                    'data' => $dataResData->data ?? []
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
