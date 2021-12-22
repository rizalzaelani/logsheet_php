<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;

class Account extends BaseController
{
    public function index()
    {
        if(!checkRoleList("APPLICATION.VIEW")){
            return View('errors/customError', ['errorCode'=>403,'errorMessage'=>"Sorry, You don't have access to this page"]);
        }

        $userId = $this->session->get("userId");
        $data = array(
            'title' => 'User Account',
            'subtitle' => 'Detail information about user'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title"    => "Account",
                "link"    => "Account"
            ],
        ];

        $parameterUser = json_decode($this->session->get("parameter"), true);

        $data["userData"] = array(
            "email" => $this->session->get("email"), 
            "role" => $this->session->get("group"), 
            "fullname" => $parameterUser['fullname'],
            "company" => $parameterUser['company'],
            "city" => $parameterUser['city'],
            "country" => $parameterUser['country'],
            "postalCode" => $parameterUser['postalCode'],
            "noTelp" => $parameterUser['noTelp'],
            "tag" => $parameterUser['tag'],
            "tagLocation" => $parameterUser['tagLocation'],
        );

        return $this->template->render('Setting/Account/index.php', $data);
    }
}