<?php

namespace App\Controllers\Api;

use App\Models\ParameterModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Parameter extends ResourceController
{
    private ParameterModel $parameterModel;

    public function __construct()
    {
        $this->parameterModel = new ParameterModel();
    }

    public function getAll()
    {
        try {
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authHeader);
            $jwtData = getJWTData($encodedToken);

            $where["userId"] = $jwtData->adminId;
            $where["deletedAt IS NULL"] = null;

            return $this->respond([
                "status"    => 200,
                "message"   => "Success Get Data Parameter",
                "data"      => $this->parameterModel->getAll($where)
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

    public function getByAsset()
    {
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
