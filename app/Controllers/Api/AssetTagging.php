<?php

namespace App\Controllers\Api;

use App\Models\AssetTaggingModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class AssetTagging extends ResourceController
{
    private AssetTaggingModel $assetTaggingModel;
    
    public function __construct()
    {
        $this->assetTaggingModel = new AssetTaggingModel();
    }

    public function getAll(){
        try {
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authHeader);
            $jwtData = getJWTData($encodedToken);

            $where["userId"] = $jwtData->adminId;

            return $this->respond([
                "status"    => 200,
                "message"   => "Success Get Data AssetTagging",
                "data"      => $this->assetTaggingModel->getAll($where)
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

        $where["assetId"] = $this->request->getVar("assetId");

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data AssetTagging",
            "data"      => $this->assetTaggingModel->getAll($where)
        ]);
    }
}