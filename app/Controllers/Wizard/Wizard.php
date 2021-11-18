<?php

namespace App\Controllers\Wizard;

use App\Controllers\BaseController;
use App\Models\PackageGroupModel;
use App\Models\PackageModel;
use App\Models\PackagePriceModel;

class Wizard extends BaseController
{
    public function index()
    {
        $packageModel = new PackageModel();
        $packageGroupModel = new PackageGroupModel();
        $packagePriceModel = new PackagePriceModel();
        $small = $packageModel->getByName(['packageGroupName' => 'small']);
        $professional = $packageModel->getByName(['packageGroupName' => 'professional']);
        $enterprise = $packageModel->getByName(['packageGroupName' => 'enterprise']);
        $package = $packageModel->getAll();
        $packageAll = $packageModel->getAllPackage();
        $packageGroup = $packageGroupModel->getAll();
        $packagePrice = $packagePriceModel->getAll();
        $data = array(
            'title' => 'Wizard Page | Logsheet Digital',
            'subtitle' => 'Wizard Logsheet Digital'
        );
        $data['package'] = $package;
        $data['packageAll'] = $packageAll;
        $data['packageGroup'] = $packageGroup;
        $data['packagePrice'] = $packagePrice;
        $data['small'] = $small;
        $data['professional'] = $professional;
        $data['enterprise'] = $enterprise;
        return $this->template->render('Wizard/index', $data);
    }

    public function register()
    {
        $post = $this->request->getPost();
        if ($post['package'] == "" || $post['fullName'] == "" || $post['companyName'] == "" || $post['typeCompany'] == "" || $post['position'] == "" || $post['numberEmployee'] == "" || $post['email'] == "" || $post['phoneNumber'] == "") {
            return $this->response->setJSON(array(
                'status' => 500,
                'message' => 'Bad Request!'
            ));
        } else {
            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Successfully Register The Application.',
                'data' => $post,
            ));
        }
        die();
    }
}
