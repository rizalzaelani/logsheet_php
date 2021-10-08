<?php

namespace App\Controllers\Api;

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
            'username' => 'required|min_length[5]',
            'appCode' => 'required|min_length[4]',
            'password' => 'required|min_length[6]'
        ]);

        if (!$isValid) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $username = $this->request->getVar("username");
        $password = $this->request->getVar("password");
        $appCode = $this->request->getVar("appCode");

        // $users = $userModel->getsByUsername($username);

        // if (empty($users)) {
        //     return $this->respond([
        //         'status' => 400,
        //         'error' => true,
        //         'message' => "Error Register. User is not registered",
        //         'data' => []
        //     ], 400);
        // }

        // // $apps = $appModel->getsByCode($appCode);

        // if (count($apps) == 0) {
        //     return $this->respond([
        //         'status' => 400,
        //         'error' => true,
        //         'message' => "Error Register. Application not exist",
        //         'data' => []
        //     ], 400);
        // }

        // $password = password_hash($password, PASSWORD_BCRYPT, [
        //     'cost' => $this->passwordSaltRound
        // ]);

        // $user = $users[0];

        if ($username != "user01" || $password != "logsheet01") {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => "Error Register. Incorect Username or Password",
                'data' => [$username, $password]
            ], 400);
        }

        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+' . getenv("JWT_TIME_TO_LIVE") . ' minutes')->getTimestamp();
        $serverName = getenv("DOMAIN_NAME");

        $jwtPayload = [
            'iss'  => $serverName,                       // Issuer
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'nbf'  => $issuedAt->getTimestamp(),         // Not before
            'exp'  => $expire,                           // Expire
            'userName' => $username,
            "data" => [
                'appCode' => $appCode,
                'username' => $username,
                'userId' => 'e7de0052-6872-4cd8-b60c-51bcc7fa4eb8'
            ]
        ];
        
        $jwt = JWT::encode($jwtPayload, getenv("JWT_SECRET_KEY"));

        return $this->respond([
            'status' => 200,
            'error' => false,
            'message' => "Authentication Successfully",
            'data' => [
                "time" => date("Y-m-d H:i:s"), 
                "token" => $jwt
            ]
        ], 200);
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
