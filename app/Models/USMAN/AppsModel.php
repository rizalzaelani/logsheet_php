<?php

namespace App\Models\USMAN;

use CodeIgniter\Model;
use HTTP_Request2;
use HTTP_Request2_Exception;

class AppsModel extends Model
{
    public function createApps($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('usmanURL') . '/api/apps/create');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'X-Requested-With' => 'XMLHTTPRequest',
        ));
        $request->addPostParameter($data);
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => $response->getBody()
                );
            } else {
                return array(
                    'success' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            throw 'Error: ' . $e->getMessage();
        }
    }
}
