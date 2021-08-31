<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;

class Tag extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Tag',
            'subtitle' => 'List Tag',
        );

        return $this->template->render('Master/Tag/index', $data);
    }
}
