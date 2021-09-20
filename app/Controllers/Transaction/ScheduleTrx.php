<?php

namespace App\Controllers\Transaction;

use App\Controllers\BaseController;
use App\Models\AssetModel;

class Transaction extends BaseController
{
    private AssetModel $assetModel;
     
    public function generateSchedule(){
        $assetModel = new AssetModel();

        $getDataAsset = $assetModel->getAll(["deletedAt IS NULL" => null]);
        foreach($getDataAsset as $row){
            
        }
    }
}

?>