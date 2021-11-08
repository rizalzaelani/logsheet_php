<?php

namespace App\Controllers\Api;

use App\Models\AssetTaggingModel;
use CodeIgniter\RESTful\ResourceController;

class AssetTagging extends ResourceController
{
    private AssetTaggingModel $assetTaggingModel;
    
    public function __construct()
    {
        $this->assetTaggingModel = new AssetTaggingModel();
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
            "message"   => "Success Get Data AssetTagging",
            "data"      => $this->assetTaggingModel->getAll($where)
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

        $where["assetId"] = $this->request->getVar("assetId");

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data AssetTagging",
            "data"      => $this->assetTaggingModel->getAll($where)
        ]);
    }
}