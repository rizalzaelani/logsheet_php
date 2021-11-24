<?php

namespace App\Models\USMAN;

use CodeIgniter\Model;
use HTTP_Request2;
use HTTP_Request2_Exception;

class UserModel extends Model
{
    public function clientAuth($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('usmanURL') . 'api/auth/auth_client');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHTTPRequest',
            'Content-Type' => 'multipart/form-data',
        ));
        $request->addPostParameter($data);
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Send Data',
                    'data' => json_decode($response->getBody())
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' =>  json_decode($response->getBody())
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => []
            );
        }
    }

    public function userList()
    {
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/users/list');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => get_cookie('clientToken')
        ));
        $request->addPostParameter(array(
            'appId' => $sess->get("appId")
        ));
        
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Get User List',
                    'data' => json_decode($response->getBody())
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' =>  json_decode($response->getBody())
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => []
            );
        }
    }

    public function refreshToken()
    {
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/auth/refresh_token');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHTTPRequest',
            'Authorization' => $sess->get("token"),
            'Content-Type' => 'multipart/form-data',
        ));
        $request->addPostParameter(array('userId' => $sess->get("userId")));

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                $dataRes = json_decode($response->getBody() ?? "[]");

                if (isset($dataRes->token)) {
                    $sess->set("token", $dataRes->token);
                    $this->response->setCookie('clientToken', $dataRes->token, 3600);

                    return array(
                        'error' => false,
                        'message' => 'Success Refresh Token',
                        'data' => $dataRes
                    );
                } else {
                    return array(
                        'error' => true,
                        'message' => "Can't Get Token, Please Try Again",
                        'data' => $dataRes
                    );
                }
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' => $response->getBody()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => '[]'
            );
        }
    }
}
