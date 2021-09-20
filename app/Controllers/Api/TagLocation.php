<?php

namespace App\Controllers\Api;

use App\Models\TagLocationModel;
use CodeIgniter\RESTful\ResourceController;

class TagLocation extends ResourceController
{
    private TagLocationModel $tagLocationModel;

    public function __construct()
    {
        $this->tagLocationModel = new TagLocationModel();
    }

    public function getAll(){
        $isValid = $this->validate([
            'userId' => 'required'
        ]);

        if (!$isValid) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $where["userId"] = $this->request->getVar("userId");

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data TagLocation",
            "data"      => $this->tagLocationModel->getAll($where)
        ]);
    }

    public function getById(){
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