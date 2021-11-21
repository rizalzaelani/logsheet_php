<?php

namespace App\Controllers\Api;

use App\Models\AssetModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Asset extends ResourceController
{
    private AssetModel $assetModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
    }

    public function getAll()
    {
        try {
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authHeader);
            $jwtData = getJWTData($encodedToken);

            $where["deletedAt IS NULL"] = null;
            $where["userId"] = $jwtData->adminId;

            return $this->respond([
                "status"    => 200,
                "message"   => "Success Get Data Asset",
                "data"      => $this->assetModel->getAll($where),
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
