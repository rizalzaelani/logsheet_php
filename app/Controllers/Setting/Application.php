<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;

class Application extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Application',
            'subtitle' => 'Setting Application'
        );
        return $this->template->render('Setting/Application/index.php', $data);
    }
}
