<?php

namespace App\Models\USMAN;

use CodeIgniter\Model;
use HTTP_Request2;
use HTTP_Request2_Exception;

class HttpRequest2Model extends Model
{
    public function doRequest($url, $method = "get", array $param, $auth = "")
    {
        $request = new HTTP_Request2();
        $request->setUrl($url);
        if($method == "post"){
            $request->setMethod(HTTP_Request2::METHOD_POST);
        } else {
            $request->setMethod(HTTP_Request2::METHOD_GET);
        }

        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $headerData['X-Requested-With'] = 'XMLHTTPRequest';
        
        if (!empty($param)) $request->addPostParameter($param);
        if (!empty($auth)) $headerData['Authorization'] = $auth;

        $request->setHeader($headerData);

        try {

            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Request Successfully',
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
            throw 'Error: ' . $e->getMessage();
        }
    }
}
