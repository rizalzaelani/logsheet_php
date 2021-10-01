<?php

namespace App\Controllers\Api;

use App\Models\AssetStatusModel;
use CodeIgniter\RESTful\ResourceController;

class AssetStatus extends ResourceController
{
    private AssetStatusModel $assetStatusModel;

    public function __construct()
    {
        $this->assetStatusModel = new AssetStatusModel();
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
            "message"   => "Success Get Data Asset Status",
            "data"      => $this->assetStatusModel->getAll($where)
        ]);
    }
}