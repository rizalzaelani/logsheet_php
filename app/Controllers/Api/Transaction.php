<?php

namespace App\Controllers\Api;
use Config\Services;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\TransactionModel;
use CodeIgniter\RESTful\ResourceController;

class Transaction extends ResourceController
{
    public function insert(){
        if(!$_POST){
            return Services::response()->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        $dataPost = file_get_contents('php://input');
        if($dataPost == "" || $dataPost == null){
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => "No Data Attached",
                'data' => []
            ], 400);
        }

        $data = json_decode($dataPost);
        
        $dataGroupSch = _group_by($data, 'scheduleTrxId');
        return $this->respond([
            'status' => 200,
            'error' => false,
            'message' => "No Data Attached",
            'data' => $dataGroupSch
        ]);

        // $insertData = [];
        // foreach($data as $row){
        //     $insertData[] = array(
        //         "trxId" => $row["trxId"],
        //         "scheduleTrxId" => $row["scheduleTrxId"],
        //         "parameterId" => $row["parameterId"],
        //         "value" => $row["value"],
        //         "condition" => "Normal"//$row["condition"]
        //     );
        // }
    }
}