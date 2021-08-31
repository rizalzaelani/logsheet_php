<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;

class Notification extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Notification',
            'subtitle' => 'List Notification IPC Logsheet'
        );
        return $this->template->render('Setting/Notification/index.php', $data);
    }
}
