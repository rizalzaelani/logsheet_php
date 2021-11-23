<?php

namespace App\Controllers\Api;

use App\Models\TagModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Tag extends ResourceController
{
    private TagModel $tagModel;

    public function __construct()
    {
        helper(['JWTAuth']);
        $this->tagModel = new TagModel();
    }

    public function getAll(){
        try {
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authHeader);
            $jwtData = getJWTData($encodedToken);

            $where["userId"] = $jwtData->adminId;

            return $this->respond([
                "status"    => 200,
                "message"   => "Success Get Data Tag",
                "data"      => $this->tagModel->getAll($where)
            ]);
        } catch (Exception $e) {
            return $this->respond([
                'status' => 500,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function getById(){
        $isValid = $this->validate([
            'tagId' => 'required'
        ]);

        if (!$isValid) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data Tag",
            "data"      => $this->tagModel->getById($this->request->getVar("tagId"))
        ]);
    }
}