<?php

namespace App\Controllers\Api;

use App\Models\ScheduleTrxModel;
use CodeIgniter\RESTful\ResourceController;
use DateTime;

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
        
        $dateTime = new DateTime();
        $year = $dateTime->format("Y");
        $month = $dateTime->format("n");
        $weekOfYear = $dateTime->format("W");

        $whereSch = "((schType = 'Daily' AND DATE(scheduleFrom) = '". $dateTime->format("Y-m-d") ."') OR (schType = 'Weekly' AND WEEKOFYEAR(scheduleFrom) = ". $weekOfYear ." AND YEAR(scheduleFrom) = " . $year . ") OR (schType = 'Monthly' AND MONTH(scheduleFrom) = ". $month ." AND YEAR(scheduleFrom) = " . $year . "))";

        $where["userId"] = $this->request->getVar("userId");
        $where[$whereSch] = null;

        return $this->respond([
            "status"    => 200,
            "message"   => "Success Get Data Schedule Transaction Today",
            "data"      => $this->scheduleTrxModel->getAll($where)
        ]);
    }
}