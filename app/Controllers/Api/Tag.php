<?php

namespace App\Controllers\Api;

use App\Models\TagModel;
use CodeIgniter\RESTful\ResourceController;

class Tag extends ResourceController
{
    private TagModel $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
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
            "message"   => "Success Get Data Tag",
            "data"      => $this->tagModel->getAll($where)
        ]);
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