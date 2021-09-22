<?php

namespace App\Controllers\Api;

use App\Models\AttachmentTrxModel;
use App\Models\ScheduleTrxModel;
use App\Models\TransactionModel;
use CodeIgniter\RESTful\ResourceController;
use DateTime;
use Exception;

class AttachmentTrx extends ResourceController
{
    public function insert(){
        $isValid = $this->validate([
            'scheduleTrxId' => 'required',
            'notes' => 'required',
        ]);

        if (!$isValid) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }

        $scheduleTrxId = $this->request->getVar('scheduleTrxId');
        $trxId = $this->request->getVar('trxId');
        $notes = $this->request->getVar('notes');
        $attachment = $this->request->getFile('attachment');

        $trxId = $trxId == "" ? null : $trxId;

        if(empty($attachment)){
            return $this->respond([
                'status' => 400,
                'error' => true,
                'message' => "No File Attached",
                'data' => []
            ], 400);
        }
        
        $scheduleTrxModel = new ScheduleTrxModel();
        $checkSch = $scheduleTrxModel->getById($scheduleTrxId);
        if(empty($checkSch)){
            return $this->respond([
                'status' => 404,
                'error' => true,
                'message' => "Schedule History Not Found",
                'data' => []
            ], 404);
        }

        if($trxId != null & $trxId != ""){
            $trxModel = new TransactionModel();
            $checkTrx = $trxModel->where("trxId", $trxId);
            if(empty($checkTrx)){
                return $this->respond([
                    'status' => 404,
                    'error' => true,
                    'message' => "Transaction Id Not Found",
                    'data' => []
                ], 404);
            }
        }

        $dirPath = "upload/attachmentMain/" . date("Y-n");
        if(!is_dir($dirPath)){
            mkdir($dirPath, 0777, TRUE);
        }

		$newfilename = $attachment->getRandomName();
		if ($attachment->move($dirPath, $newfilename)) {

			$attachmentTrxModel = new AttachmentTrxModel();

			$dataInsert = [
                "attachmentTrxId" => null,
				"scheduleTrxId" => $scheduleTrxId,
				"trxId" => $trxId,
				"notes" => $notes,
				"attachment" => base_url() . "/" . $dirPath . "/" . $newfilename
			];

            $checkAttachSch = $attachmentTrxModel->where("scheduleTrxId", $scheduleTrxId)->orderBy("createdAt", "desc")->get()->getResultArray();
            if(!empty($checkAttachSch)){
                $dateTime1Hour = new DateTime(); 
                foreach($checkAttachSch as $rowAttach){
                    $fileTemp = str_replace(base_url() . "/", "", $rowAttach["attachment"]);
                    if(file_exists($fileTemp)){
                        unlink($fileTemp);
                    }
                }

                $attachmentTrxModel->delete(["scheduleTrxId" => $scheduleTrxId]);
            }

			$attachmentTrxModel->insert($dataInsert);
            return $this->respond([
                'status' => 200,
                'error' => false,
                'message' => 'File uploaded successfully',
                'data' => [
                    "newfilename" => $newfilename,
                    "fileLoc" => base_url() . "/" . $dirPath . "/" . $newfilename
                ]
            ], 200);
		} else {
            return $this->respond([
				'status' => 500,
				'error' => true,
				'message' => 'Failed to upload Attachment',
				'data' => []
			], 500);
		}
    }
}