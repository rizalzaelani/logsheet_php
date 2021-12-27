<?php
use App\Models\Influx\LogModel;

function sendLog($activity, $assetId, $data){
    $logModel = new LogModel();
    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $ip = $request->getIPAddress();
    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    return $logModel->writeData($activity, $ip, $userId, $username, $assetId, $data);
}

?>