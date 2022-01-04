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

    public function refreshToken()
    {
        $sess = \Config\Services::session();
        $resp = \Config\Services::response();

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
                    $resp->setCookie("clientToken", "active", 3600);

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
                if ($response->getStatus() == 401) {
                    $clientAuth = $this->clientAuth(array(
                        'appCode' => $sess->get("appCode"),
                        'email' => $sess->get("email"),
                        'password' => $sess->get("password")
                    ));


                    $dataCA = $clientAuth['data'];
                    if ($clientAuth['error']) {
                        return array(
                            'error' => true,
                            'status' => isset($dataCA->message) ? 400 : 500,
                            'message' => $dataCA->message ?? $clientAuth['message'],
                            'data' => $dataCA
                        );
                    } else {
                        $sess->set("token", $dataCA->data->token);
                        $resp->setCookie("clientToken", "active", 3600);

                        return array(
                            'error' => false,
                            'message' => 'Success Refresh Token',
                            'data' => $dataCA
                        );
                    }
                } else {
                    return array(
                        'error' => true,
                        'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                        'data' => $response->getBody()
                    );
                }
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
            'Authorization' => $sess->get("token")
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

    public function userDetail($userId = "")
    {
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/users/getUserDetail');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => $sess->get("token")
        ));
        $request->addPostParameter(array(
            'appId' => $sess->get("appId"),
            'userId' => $userId
        ));

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Get User Detail',
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

    public function saveUser($param)
    {
        // for update group, add the groupId value
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/users/create');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => $sess->get("token")
        ));
        $request->addPostParameter($param);

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Save User',
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

    public function deleteUser($param)
    {
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/users/delete_user');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => $sess->get("token")
        ));
        $request->addPostParameter($param);

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Delete User',
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

    public function changePassword($param)
    {
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/auth/change_password');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => $sess->get("token")
        ));
        $request->addPostParameter($param);

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Change Password',
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

    public function forgotPassword($param)
    {
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/auth/get_email_token');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHttpRequest'
        ));
        $request->addPostParameter($param);

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Get Token Forgot Password',
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

    public function resetPassword($token, $param)
    {
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/auth/confirm_change_password');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization' => $token
        ));
        $request->addPostParameter($param);

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Reset Password',
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
}
