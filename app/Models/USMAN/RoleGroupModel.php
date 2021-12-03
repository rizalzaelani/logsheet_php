<?php

namespace App\Models\USMAN;

use CodeIgniter\Model;
use HTTP_Request2;
use HTTP_Request2_Exception;

class RoleGroupModel extends Model
{
    public function groupList(){
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/groups/list');
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
                    'message' => 'Success Get Group List',
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
    
    public function groupDetail($groupId=''){
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/groups/detail');
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
            'groupId' => $groupId
        ));
        
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Get Group Detail',
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

    public function roleList(){
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/roles/list');
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
                    'message' => 'Success Get Group List',
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

    public function saveGroup($param){
        // for update group, add the groupId value
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/groups/create');
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
                    'message' => 'Success Add Group',
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

    public function deleteGroup($param){
        $sess = \Config\Services::session();
        $request = new HTTP_Request2();

        $request->setUrl(env('usmanURL') . 'api/group/delete_group');
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
                    'message' => 'Success Delete Group',
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
