<?php

namespace App\Controllers\UserRole;

use App\Controllers\BaseController;
use App\Models\USMAN\RoleGroupModel;
use App\Models\USMAN\UserModel;
use Exception;

class Role extends BaseController
{
	public function groupList()
	{
		try {
            $userModel = new UserModel();
            $roleGroupModel = new RoleGroupModel();

            $clientToken = get_cookie("clientToken");
            if(!isset($clientToken) || $clientToken == null){
                $resRT = $userModel->refreshToken();
                if($resRT['error']){
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
                    'error' => true,
                    'message' => "Success Get User List",
                    'data' => $data
                ], 200);
            }
        } catch (Exception $e){
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
	}

	public function roleList()
	{
		try {
            $userModel = new UserModel();
            $roleGroupModel = new RoleGroupModel();

            $clientToken = get_cookie("clientToken");
            if(!isset($clientToken) || $clientToken == null){
                $resRT = $userModel->refreshToken();
                if($resRT['error']){
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
                    'message' => "Success Get User List",
                    'data' => $data
                ], 200);
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