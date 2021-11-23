<?php

namespace App\Controllers\Api;

use App\Models\AssetStatusModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class AssetStatus extends ResourceController
{
    private AssetStatusModel $assetStatusModel;

    public function __construct()
    {
        helper(['JWTAuth']);
        $this->assetStatusModel = new AssetStatusModel();
    }

    public function getAll(){
        try {
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authHeader);
            $jwtData = getJWTData($encodedToken);

            $where["userId"] = $jwtData->adminId;
            return $this->respond([
                "status"    => 200,
                "message"   => "Success Get Data Asset Status",
                "data"      => $this->assetStatusModel->getAll($where)
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
}