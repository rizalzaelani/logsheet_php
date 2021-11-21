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
                    'data' => $response->getBody()
                );
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
