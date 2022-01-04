<?php

use App\Models\AssetModel;
use App\Models\ParameterModel;
use App\Models\TagLocationModel;
use App\Models\TagModel;
use App\Models\USMAN\UserModel;
use App\Models\Wizard\SubscriptionModel;


function getSubscription()
{
    $subscriptionModel  = new SubscriptionModel();
    $assetModel         = new AssetModel();
    $tagModel           = new TagModel();
    $tagLocationModel   = new TagLocationModel();

    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    $dataSubscription = $subscriptionModel->getAll(['userId' => $userId]);
    return $dataSubscription; 
}

function listUser()
{
    $dt = [];
    try {
        $userModel = new UserModel();
        $clientToken = get_cookie("clientToken");
        if (!isset($clientToken) || $clientToken == null) {
            $resRT = $userModel->refreshToken();
            if ($resRT['error']) {
                return $dt;
            }
        }
        $dataRes = $userModel->userList();

        $data = $dataRes['data'];
        if ($dataRes['error']) {
            return $dt;
        } else {
            return $data->data;
        }
    } catch (Exception $e) {
        return "";
    }
}

function checkLimitAsset(){
    $assetModel         = new AssetModel();

    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    $dataSubscription   = getSubscription()[0];
    $assetMax           = $dataSubscription['assetMax'];

    $dataAsset = $assetModel->getAll(['userId' => $userId]);
    $jmlAsset = count($dataAsset);

    if ($jmlAsset >= $assetMax) {
        return false;
    }else{
        return true;
    }
}

function checkLimitParameter(){
    $parameterModel = new ParameterModel();

    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    $dataSubscription = getSubscription()[0];
    $parameterMax   = $dataSubscription['parameterMax'];
    $dataParameter = $parameterModel->getAll(['userId' => $userId]);
    $jmlParameter = count($dataParameter);

    if ($jmlParameter >= $parameterMax) {
        return false;
    }else{
        return true;
    }
}

function checkLimitTag($tag = null){
    $tagModel           = new TagModel();

    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    $dataSubscription = getSubscription()[0];
    $tagMax         = $dataSubscription['tagMax'];
    $dataTag = $tagModel->getAll(['userId' => $userId]);
    $jmlTag = count($dataTag);

    if ($tag != null) {
        $jmlTag = $jmlTag + $tag;
    }

    if ($jmlTag >= $tagMax) {
        return false;
    }else{
        return true;
    }
}

function checkLimitTagLocation(){
    $tagLocationModel   = new TagLocationModel();

    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    $dataSubscription = getSubscription()[0];
    $tagLocationgMax = $dataSubscription['tagMax'];
    $dataTagLocation = $tagLocationModel->getAll(['userId' => $userId]);
    $jmlTagLocation = count($dataTagLocation);

    if ($jmlTagLocation >= $tagLocationgMax) {
        return false;
    }else{
        return true;
    }
}

function checkLimitUser(){

    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    $dataSubscription = getSubscription()[0];
    $userMax        = $dataSubscription['userMax'];

    if (listUser()) {
        $jmlUser = count(listUser());
        if ($jmlUser >= $userMax) {
            return false;
        }else{
            return true;
        }
    }
}

function checkLimitTransaction(){
    $tagLocationModel   = new TagLocationModel();

    $sess = \Config\Services::session();
    $request = \Config\Services::request();

    $userId = $sess->get('adminId');
    $username = $sess->get('name');

    $dataSubscription = getSubscription()[0];
    $trxDailyMax    = $dataSubscription['trxDailyMax'];
}

?>