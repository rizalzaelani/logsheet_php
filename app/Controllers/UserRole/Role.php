<?php

namespace App\Controllers\UserRole;

use App\Controllers\BaseController;
use App\Models\USMAN\RoleGroupModel;
use App\Models\USMAN\UserModel;
use Exception;

class Role extends BaseController
{
    public function index()
    {
        if(!checkRoleList("ROLE.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

        $data = array(
            'title' => 'Role Logsheet Digital',
            'subtitle' => 'All Role Logsheet'
        );

        return $this->template->render('UserRole/Role/index', $data);
    }

    public function detail()
    {
        if(!checkRoleList("ROLE.DETAIL.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

        $data = array(
            'title' => 'Add New Role',
            'subtitle' => 'Detail Role'
        );
        $groupId = $this->request->getGet("groupId") ?? "";

        try {
            $userModel = new UserModel();
            $roleGroupModel = new RoleGroupModel();

            $clientToken = get_cookie("clientToken");
            if (!isset($clientToken) || $clientToken == null) {
                $resRT = $userModel->refreshToken();
                if ($resRT['error']) {
                    $data["error"] = true;
                    $data["message"] = $resRT['data']->message ?? $resRT['message'];
                }
            }


            if ($groupId != "") {
                $dataRes = $roleGroupModel->groupDetail($groupId);
                $dataGD = $dataRes['data'];
                if ($dataRes['error']) {
                    $data["error"] = true;
                    $data["message"] = $dataGD->message ?? $dataRes['message'];
                } else {
                    $data['title'] = $dataGD->data->name ?? $data["title"];
                    $data['groupData'] = $dataGD->data ?? [];
                }
            }

            $dataResR = $roleGroupModel->roleList();
            $dataRD = $dataResR['data'];
            if ($dataResR['error']) {
                $data["error"] = true;
                $data["message"] = $dataRD->message ?? $dataResR['message'];
            } else {
                $data['roleData'] = $dataRD->data ?? [];
            }
        } catch (Exception $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
        }

        return $this->template->render('UserRole/Role/detail', $data);
    }

    public function groupList()
    {
        if(!checkRoleList("ROLE.VIEW")){
        	return $this->response->setJSON([
        		'status' => 403,
                'message' => "Sorry, You don't have access",
        		'data' => []
        	], 403);
        }

        try {
            $userModel = new UserModel();
            $roleGroupModel = new RoleGroupModel();

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
            $dataRes = $roleGroupModel->groupList();

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
                    'message' => "Success Get Group List",
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

    public function roleList()
    {
        if(!checkRoleList("ROLE.DETAIL.VIEW")){
        	return $this->response->setJSON([
        		'status' => 403,
                'message' => "Sorry, You don't have access",
        		'data' => []
        	], 403);
        }

        try {
            $userModel = new UserModel();
            $roleGroupModel = new RoleGroupModel();

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
            $dataRes = $roleGroupModel->roleList();

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
                    'message' => "Success Get Role List",
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

    public function saveGroup()
    {
        $groupId = $this->request->getVar("groupId") ?? "";
        if(($groupId == "" && !checkRoleList("ROLE.ADD")) || ($groupId != "" && !checkRoleList("ROLE.MODIFY"))){
        	return $this->response->setJSON([
        		'status' => 403,
                'message' => "Sorry, You don't have access",
        		'data' => []
        	], 403);
        }

        $isValid = $this->validate([
            'name' => 'required',
            'roleId' => 'required'
        ]);

        if (!$isValid) {
            return $this->response->setJson([
                'status' => 400,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $name = $this->request->getVar("name");
        $roleId = $this->request->getVar("roleId");

        $userModel = new UserModel();
        $roleGroupModel = new RoleGroupModel();

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
            $param["roleId"] = $roleId;
            $param["description"] = "-";
            $param["appId"] = $this->session->get("appId");

            if ($groupId != "") {
                $param["groupId"] = $groupId;
            }
            
            $dataRes = $roleGroupModel->saveGroup($param);

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
                    'message' => "Success " . ($groupId != "" ? "update" : "create") . " group",
                    'data' => $data->data ?? [],
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

    public function deleteGroup(){
        if(!checkRoleList("ROLE.DELETE")){
        	return $this->response->setJSON([
        		'status' => 403,
                'message' => "Sorry, You don't have access",
        		'data' => []
        	], 403);
        }

        $isValid = $this->validate([
            'groupId' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid",
                'data' => $this->validator->getErrors()
            ], 400);
        }
        
        $userModel = new UserModel();
        $roleGroupModel = new RoleGroupModel();

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
            
            $param["groupId"] = $this->request->getVar("groupId");
            $param["appId"] = $this->session->get("appId");
            $param["force"] = true;
            $dataRes = $roleGroupModel->deleteGroup($param);

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
                    'message' => "Success Delete Group",
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
}
