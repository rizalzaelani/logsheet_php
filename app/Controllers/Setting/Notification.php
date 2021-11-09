<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;

class Notification extends BaseController
{
    public function index()
    {
        if(!checkRoleList("NOTIFICATION.VIEW")){
            return View('errors/customError', ['ErrorCode'=>403,'ErrorMessage'=>"Sorry, You don't have access to this page"]);
        }

        $data = array(
            'title' => 'Notification',
            'subtitle' => 'List Notification IPC Logsheet'
        );
        return $this->template->render('Setting/Notification/index.php', $data);
    }
}
