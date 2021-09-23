<?php

namespace App\Controllers\Api;

use App\Models\ParameterModel;
use CodeIgniter\RESTful\ResourceController;

class Parameter extends ResourceController
{
    private ParameterModel $parameterModel;
    
    public function __construct()
    {
        $this->parameterModel = new ParameterModel();
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
            "message"   => "Success Get Data Parameter",
            "data"      => $this->parameterModel->getAll($where)
        ]);
    }

    public function getByAsset(){
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

        $where["deletedAt IS NULL"] = null;
        $where["assetId"] = $this->request->getVar("assetId");

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data Parameter",
            "data"      => $this->parameterModel->getAll($where)
        ]);
    }
}