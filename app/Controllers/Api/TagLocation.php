<?php

namespace App\Controllers\Api;

use App\Models\TagLocationModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class TagLocation extends ResourceController
{
    private TagLocationModel $tagLocationModel;

    public function __construct()
    {
        $this->tagLocationModel = new TagLocationModel();
    }

    public function getAll()
    {
        try {
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authHeader);
            $jwtData = getJWTData($encodedToken);

            $where["userId"] = $jwtData->adminId;

            return $this->respond([
                "status"    => 200,
                "message"   => "Success Get Data TagLocation",
                "data"      => $this->tagLocationModel->getAll($where)
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

    public function getById()
    {
        $isValid = $this->validate([
            'tagLocationId' => 'required'
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
            "message"   => "Success Get Data TagLocation",
            "data"      => $this->tagLocationModel->getById($this->request->getVar("tagLocationId"))
        ]);
    }
}
