<?php

namespace App\Controllers\Api;

use App\Models\ScheduleTrxModel;
use CodeIgniter\RESTful\ResourceController;
use DateTime;
use Exception;

class ScheduleTrx extends ResourceController
{
    private ScheduleTrxModel $scheduleTrxModel;

    public function __construct()
    {
        $this->scheduleTrxModel = new ScheduleTrxModel();
    }

    public function getAll(){
        try {
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authHeader);
            $jwtData = getJWTData($encodedToken);
        
            $dateTime = new DateTime();
            $year = $dateTime->format("Y");
            $month = $dateTime->format("n");
            $weekOfYear = $dateTime->format("W");

            $whereSch = "((((schType = 'Daily' AND DATE(scheduleFrom) = '". $dateTime->format("Y-m-d") ."') OR (schType = 'Weekly' AND WEEKOFYEAR(scheduleFrom) = ". $weekOfYear ." AND YEAR(scheduleFrom) = " . $year . ") OR (schType = 'Monthly' AND MONTH(scheduleFrom) = ". $month ." AND YEAR(scheduleFrom) = " . $year . ")) AND schManual = '0') OR (CAST(scheduleFrom as DATE) <= CAST('" . $dateTime->format("Y-m-d") . "' as DATE) AND CAST(scheduleTo as DATE) >= CAST('" . $dateTime->format("Y-m-d") . "' as DATE) AND schManual = '1'))";

            $where["userId"] = $jwtData->adminId;
            $where[$whereSch] = null;

            return $this->respond([
                "status"    => 200,
                "message"   => "Success Get Data Schedule Transaction Today",
                "data"      => $this->scheduleTrxModel->getAll($where)
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