<?php

namespace App\Controllers\Api;

use App\Models\USMAN\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use DateTimeImmutable;
use Exception;

class Login extends ResourceController
{
    // get all product
    public function index()
    {
        $isValid = $this->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!$isValid) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $param["email"] = $this->request->getVar("email");
        $param["password"] = $this->request->getVar("password");
        
        $userModel = new UserModel();
        $dataRes = $userModel->clientAuth($param);
        
        $data = $dataRes['data'];
        if ($dataRes['error']) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => $data->message ?? $dataRes['message'],
                'data' => []
            ], 400);
        } else {
            $issuedAt   = new DateTimeImmutable();
            $expire     = $issuedAt->modify('+' . getenv("JWT_TIME_TO_LIVE") . ' minutes')->getTimestamp();
            $serverName = getenv("DOMAIN_NAME");

            $jwtPayload = [
                'iss'  => $serverName,                       // Issuer
                'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
                'nbf'  => $issuedAt->getTimestamp(),         // Not before
                'exp'  => $expire,                           // Expire
                'email' => $param["email"],
                "data" => array(
                    "userId" => $data->data->userId,
                    "name" => $data->data->name,
                    "email" => $data->data->email,
                    "parameter" => json_decode($data->data->parameter ?? "{}"),
                    "group" => $data->data->group,
                    "appId" => $data->data->appId,
                    "appCode" => $data->data->appCode,
                    "adminId" => $data->data->adminId,
                )
            ];
            
            $jwt = JWT::encode($jwtPayload, getenv("JWT_SECRET_KEY"));
            return $this->respond([
                'status' => 200,
                'error' => false,
                'message' => "Authentication Successfully",
                'data' => [
                    "time" => date("Y-m-d H:i:s"), 
                    "token" => $jwt,
                    "dataUser" => array(
                        "userId" => $data->data->userId,
                        "name" => $data->data->name,
                        "email" => $data->data->email,
                        "parameter" => json_decode($data->data->parameter ?? "{}"),
                        "group" => $data->data->group,
                    )
                ]
            ], 200);
        }
    }

    public function logout()
    {
        return $this->respond([
            'status' => 200,
            'error' => false,
            'message' => "Logout Successfully",
            'data' => []
        ], 400);
    }
}
