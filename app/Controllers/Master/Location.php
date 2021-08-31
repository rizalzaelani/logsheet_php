<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;

class Location extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Location',
            'subtitle' => 'List Location',
        );

        return $this->template->render('Master/Location/index', $data);
    }
}
