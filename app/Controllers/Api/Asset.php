<?php

namespace App\Controllers\Api;

use App\Models\AssetModel;
use CodeIgniter\RESTful\ResourceController;

class Asset extends ResourceController
{
    private AssetModel $assetModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
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

        $where["deletedAt IS NULL"] = null;
        $where["userId"] = $this->request->getVar("userId");

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data Asset",
            "data"      => $this->assetModel->getAll($where)
        ]);
    }

    public function getById(){
        $isValid = $this->validate([
            'assetId' => 'required'
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
            "message"   => "Success Get Data Asset",
            "data"      => $this->assetModel->getById($this->request->getVar("assetId"))
        ]);
    }
}