<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;

class Schedule extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Schedule',
            'subtitle' => 'Schedule'
        );
        $data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
			[
				"title"	=> "Schedule",
				"link"	=> "Schedule"
			],
		];
        return $this->template->render('Setting/Schedule/index.php', $data);
    }
}
