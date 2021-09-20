<?php

namespace App\Controllers\Api;

use App\Models\ScheduleTrxModel;
use CodeIgniter\RESTful\ResourceController;

class ScheduleTrx extends ResourceController
{
    private ScheduleTrxModel $scheduleTrxModel;

    public function __construct()
    {
        $this->scheduleTrxModel = new ScheduleTrxModel();
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
        $where["scheduleFrom"] = date("Y-m-d");

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data Schedule Transactio Today",
            "data"      => $this->scheduleTrxModel->getAll($where)
        ]);
    }
}