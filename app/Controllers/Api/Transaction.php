<?php

namespace App\Controllers\Api;

use App\Models\ScheduleTrxModel;

use App\Models\TransactionModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class Transaction extends ResourceController
{
    public function insert(){
        // if(!$_POST){
        //     return Services::response()->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        // }

        $scheduleTrxModel = new ScheduleTrxModel();
        $transactionModel = new TransactionModel();

        helper("main");
        $dataPost = file_get_contents('php://input');
        if($dataPost == "" || $dataPost == null){
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => "No Data Attached",
                'data' => []
            ], 400);
        }

        $data = json_decode($dataPost, true);
        
        $dataGroupSch = _group_by($data, 'scheduleTrxId');
        $schTrxIdArr = array_keys($dataGroupSch);

        $getSchedule = $scheduleTrxModel->whereIn("scheduleTrxId", $schTrxIdArr)->get()->getResultArray();
        $getTrx = $transactionModel->whereIn("scheduleTrxId", $schTrxIdArr)->get()->getResultArray();

        $insertTrxData = [];
        $updateTrxData = [];
        $updateSchData = [];
        $sch404 = [];
        $sch200 = [];

        foreach($dataGroupSch as $k => $row){
            $filtSch = array_filter($getSchedule, function($val) use ($k) {
                return $val["scheduleTrxId"] == $k;
            });

            if(!empty($filtSch)){
                array_push($updateSchData, array(
                    "scheduleTrxId" => $k,
                    "syncAt"        => $row[0]["syncAt"],
                    "scannedAt"     => $row[0]["scannedAt"],
                    "scannedEnd"    => $row[0]["scannedEnd"],
                    "scannedBy"     => $row[0]["scannedBy"],
                    "scannedWith"   => $row[0]["scannedWith"],
                    "scannedNotes"  => $row[0]["scannedNotes"]
                ));
                
                array_push($sch200, array("scheduleId" => $k));

                foreach($row as $rowTrx){
                    $filtTrx = array_values(array_filter($getTrx, function($val) use ($rowTrx){
                        return $val["scheduleTrxId"] == $rowTrx["scheduleTrxId"] && $val["parameterId"] == $rowTrx["parameterId"];
                    }));

                    if(empty($filtTrx)){
                        array_push($insertTrxData, array(
                            "trxId" => $rowTrx["trxId"],
                            "scheduleTrxId" => $rowTrx["scheduleTrxId"],
                            "parameterId" => $rowTrx["parameterId"],
                            "value" => $rowTrx["value"],
                            "condition" => "Normal"//$row["condition"]
                        ));
                    } else {
                        array_push($updateTrxData, array(
                            "trxId" => $filtTrx[0]["trxId"],
                            "scheduleTrxId" => $rowTrx["scheduleTrxId"],
                            "parameterId" => $rowTrx["parameterId"],
                            "value" => $rowTrx["value"],
                            "condition" => "Normal"//$row["condition"]
                        ));
                    }
                }
            } else {
                array_push($sch404, array("scheduleId" => $k));
            }
        }

        try{
            if(!empty($insertTrxData)){
                $transactionModel->insertBatch($insertTrxData);
            }
            if(!empty($updateTrxData)){
                $transactionModel->updateBatch($updateTrxData, 'trxId');
            }
            if(!empty($updateSchData)){
                $scheduleTrxModel->updateBatch($updateSchData, 'scheduleTrxId');
            }

            return $this->respond([
                'status' => 200,
                'error' => false,
                'message' => "Success Insert Record",
                'data' => [
                    // "insertTrxData" => $insertTrxData,
                    // "updateTrxData" => $updateTrxData,
                    // "updateSchData" => $updateSchData,
                    "sch404" => $sch404,
                    "sch200" => $sch200
                ]
            ], 200);
        } catch(Exception $e){
            return $this->respond([
                'status' => 500,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}