<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\USMAN\AppsModel;
use Exception;
use HTTP_Request2;
use HTTP_Request2_Exception;

class Register extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Register Page | Logsheet Digital',
            'subtitle' => 'Logsheet Digital'
        );

        return $this->template->render('Auth/register', $data);
    }

    public function doRegister()
    {
        $isValid = $this->validate([
            'fullname' => 'required',
            'noTelp' => 'required',
            'email' => 'required',
            'password' => 'required',
            'company' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'country' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJSON([
                'status' => 400,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ], 400);
        }
        

        $param["name"] = $this->request->getVar('appName');
        $param["code"] = str_replace(" ", "-", strtolower($param["name"]));
        $param["description"] = "-";

        $param['email'] = $this->request->getVar('email');
        $param['password'] = $this->request->getVar('password');
        $param['confirm_password'] = $this->request->getVar('password');
        $param['app_url'] = base_url("/");
        $param['app_group'] = "logsheet";

        $param['parameter[fullname]'] = $this->request->getVar('fullname');
        $param['parameter[noTelp]'] = $this->request->getVar('noTelp');
        $param['parameter[company]'] = $this->request->getVar('company');
        $param['parameter[city]'] = $this->request->getVar('city');
        $param['parameter[postalCode]'] = $this->request->getVar('postalCode');
        $param['parameter[country]'] = $this->request->getVar('country');
        $param['parameter[tag]'] = "";
        $param['parameter[tagLocation]'] = "";
        
        $param['group'] = "Superadmin";
        $param['role'] = '[{"code":"APPLICATION.ASSETSTATUS.DELETE","name":"DELETE","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.ASSETSTATUS.MODIFY","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.ASSETSTATUS.RESTORE","name":"RESTORE","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.MODIFY","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"DASHBOARD.VIEW","name":"VIEW","description":null,"parent1":"DASHBOARD","parent2":null,"type":"client"},{"code":"FINDING.CLOSE","name":"CLOSE","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.DETAIL.LIST.VIEW","name":"LIST PARAMETER","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.DETAIL.VIEW","name":"DETAIL","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.LOG.ADD","name":"CREATE","description":null,"parent1":"FINDING","parent2":"TIMELINE","type":"client"},{"code":"FINDING.LOG.LIST","name":"VIEW","description":null,"parent1":"FINDING","parent2":"TIMELINE","type":"client"},{"code":"FINDING.OPEN","name":"OPEN","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.VIEW","name":"VIEW","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"LOGACTIVITY.VIEW","name":"VIEW","description":null,"parent1":"LOG ACTIVITY","parent2":null,"type":"client"},{"code":"MASTER.ASSET.ADD","name":"CREATE","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.DELETE","name":"REMOVE","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.DETAIL","name":"DETAIL","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.PARAMETER.IMPORT.SAMPLE","name":"IMPORT PARAMETER","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.PARAMETER.SORT","name":"SORTING PARAMETER","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.UPDATE","name":"MODIFY","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.VIEW","name":"VIEW","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.ADD","name":"CREATE","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.DELETE","name":"REMOVE","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.IMPORT","name":"IMPORT","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.UPDATE","name":"MODIFY","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.VIEW","name":"VIEW","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.ADD","name":"CREATE","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.DELETE","name":"REMOVE","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.DETAIL","name":"DETAIL","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.IMPORT","name":"IMPORT","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.UPDATE","name":"MODIFY","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.VIEW","name":"VIEW","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"NOTIFICATION.ADD","name":"CREATE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.DELETE","name":"DELETE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.MODIFY","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.MODIFY.STATUS","name":"PAUSE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.RESTORE","name":"RESTORE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"REPORT.ASSET.DETAIL","name":"DETAIL","description":null,"parent1":"ASSET","parent2":"REPORT","type":"client"},{"code":"REPORT.ASSET.VIEW","name":"VIEW","description":null,"parent1":"ASSET","parent2":"REPORT","type":"client"},{"code":"REPORT.RAWDATA.VIEW","name":"RAWDATA","description":null,"parent1":"REPORT","parent2":null,"type":"client"},{"code":"ROLE.ADD","name":"CREATE","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.DELETE","name":"DELETE","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.DETAIL.VIEW","name":"DETAIL","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.MODIFY","name":"MODIFY","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.VIEW","name":"VIEW","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"SCHEDULE.ADD","name":"ADD","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"SCHEDULE.IMPORT","name":"IMPORT","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"SCHEDULE.LIST","name":"LIST","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"SCHEDULE.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"TRX.APPROVE","name":"APPROVE","description":null,"parent1":"TRANSACTION","parent2":null,"type":"client"},{"code":"TRX.DETAIL.VIEW","name":"DETAIL","description":null,"parent1":"TRANSACTION","parent2":null,"type":"client"},{"code":"TRX.VIEW","name":"VIEW","description":null,"parent1":"TRANSACTION","parent2":null,"type":"client"},{"code":"USER.ADD","name":"CREATE","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"USER.DELETE","name":"DELETE","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"USER.MODIFY","name":"MODIFY","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"USER.VIEW","name":"VIEW","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"VERSIONAPPS.DELETE","name":"REMOVE","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.DETAIL","name":"DETAIL","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.DOWNLOAD","name":"DOWNLOAD","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.RELEASE","name":"RELEASE","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.UPDATE","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"}]';
        $param['roleGroup[Superadmin]'] = getenv("ROLELIST");

        try {
            $appModel = new AppsModel();
            $dataRes = $appModel->createApps($param);
            
            $data = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($data->message) ? 400 : 500,
                    'message' => $data->message ?? $dataRes['message'],
                    'data' => $data
                ), isset($data->message) ? 400 : 500);
            } else {
                return $this->response->setJSON([
                    'status' => 200,
                    'message' => "Success Create App",
                    'data' => $data
                ], 200);
            }
        } catch (Exception $e){
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }
}
