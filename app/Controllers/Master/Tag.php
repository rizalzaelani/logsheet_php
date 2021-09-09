<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\TagModel;

class Tag extends BaseController
{
    public function index()
    {
        $model = new TagModel();
        $data = $model->findAll();
        echo $data;
        // $data = array(
        //     'title' => 'Tag',
        //     'subtitle' => 'List Tag',
        // );

        // return $this->template->render('Master/Tag/index', $data);
    }
}
