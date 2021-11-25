<?php

namespace App\Controllers\UserRole;

use App\Controllers\BaseController;
use App\Models\USMAN\RoleGroupModel;
use App\Models\USMAN\UserModel;
use Exception;

class User extends BaseController
{
	public function index()
	{
        // if(!checkRoleList("USER.VIEW")){
        //     return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        // }

		$data = array(
			'title' => 'User Logsheet Digital',
			'subtitle' => 'All User Logsheet'
		);

		return $this->template->render('UserRole/User/index', $data);
	}

	public function userList()
	{
		try {
            $userModel = new UserModel();
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
        } catch (Exception $e){
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
	}

    public function getUserById()
	{
		try {
            $userModel = new UserModel();
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
        } catch (Exception $e){
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
	}
}