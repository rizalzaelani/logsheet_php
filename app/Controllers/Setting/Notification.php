<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class Notification extends BaseController
{
    public function index()
    {
        if (!checkRoleList("NOTIFICATION.VIEW")) {
            return View('errors/customError', ['ErrorCode' => 403, 'ErrorMessage' => "Sorry, You don't have access to this page"]);
        }

        $data = array(
            'title' => 'Notification',
            'subtitle' => 'Notification'
        );
        return $this->template->render('Setting/Notification/index.php', $data);
    }

    public function datatable()
    {
        $request = \Config\Services::request();

        // if(!checkRoleList("MASTER.TAG.VIEW")){
        // 	echo json_encode(array(
        // 		"draw" => $request->getPost('draw'),
        // 		"recordsTotal" => 0,
        // 		"recordsFiltered" => 0,
        // 		"data" => [],
        // 		'status' => 403,
        // 		'message' => "You don't have access to this page"
        // 	));
        // }

        $table = "tblm_notification";
        $column_order = array('type', 'value', 'trigger', 'notificationId');
        $column_search = array('type', 'value', 'trigger', 'notificationId');
        $order = array('createdAt' => 'asc');
        $DTModel = new \App\Models\DatatableModel($table, $column_order, $column_search, $order);
        $where = [
            'userId' => $this->session->get("adminId"),
            'deletedAt IS ' . (($this->request->getVar("deleted") ?? "0") == "1" ? "NOT " : "") . 'NULL' => null
        ];
        $list = $DTModel->datatable($where);
        $output = array(
            "draw" => $request->getPost('draw'),
            "recordsTotal" => $DTModel->count_all($where),
            "recordsFiltered" => $DTModel->count_filtered($where),
            "data" => $list,
            "status" => 200,
            "message" => "success"
        );
        echo json_encode($output);
    }

    public function saveNotif()
    {
        // if(!checkRoleList("MASTER.TAG.ADD")){
        // 	return $this->response->setJSON([
        // 		'status' => 403,
        //         'message' => "Sorry, You don't have access",
        // 		'data' => []
        // 	], 403);
        // }

        $isValid = $this->validate([
            "friendlyName" => "required",
            "type" => "required",
            "trigger" => "required",
            "value" => "required",
        ]);

        if (!$isValid) {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid",
                'data' => $this->validator->getErrors()
            ], 400);
        }

        $notifId = $this->request->getVar("notificationId") ?? "";
        $dt = array(
            "userId" => $this->session->get("adminId"),
            "friendlyName" => $this->request->getVar("friendlyName"),
            "type" => $this->request->getVar("type"),
            "trigger" => $this->request->getVar("trigger"),
            "value" => $this->request->getVar("value"),
            "status" => "active",
        );

        $notifModel = new NotificationModel();
        if ($notifId != "") {
            $notifModel->update($notifId, $dt);
        } else {
            $dt["notificationId"] = null;
            $notifModel->insert($dt);
        }

        return $this->response->setJson([
            'status' => 200,
            'message' => "Success " . ($notifId == "" ? "Create" : "Update") . " Notification",
            'data' => []
        ], 200);
    }

    public function changeStatus()
    {
        // if(!checkRoleList("MASTER.TAG.DELETE")){
        // 	return $this->response->setJSON([
        // 		'status' => 403,
        //         'message' => "Sorry, You don't have access",
        // 		'data' => []
        // 	], 403);
        // }

        $notifModel = new NotificationModel();
        $notificationId = $this->request->getVar("notificationId");
        $status = $this->request->getVar("status") ?? "active";
        if ($notificationId != '') {
            $notifModel->update($notificationId, ["status" => $status]);

            return $this->response->setJson([
                'status' => 200,
                'message' => "Success Restore Notification",
                'data' => []
            ], 200);
        } else {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid (NotificationID)",
                'data' => []
            ], 400);
        }
    }

    public function deleteNotif()
    {
        // if(!checkRoleList("MASTER.TAG.DELETE")){
        // 	return $this->response->setJSON([
        // 		'status' => 403,
        //         'message' => "Sorry, You don't have access",
        // 		'data' => []
        // 	], 403);
        // }

        $notifModel = new NotificationModel();
        $notificationId = $this->request->getVar("notificationId");
        if ($notificationId != '') {
            $notifModel->delete($notificationId, ($this->request->getVar("hard") == "1" ? true : false));

            return $this->response->setJson([
                'status' => 200,
                'message' => "Success Delete Notification",
                'data' => []
            ], 200);
        } else {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid (NotificationID)",
                'data' => []
            ], 400);
        }
    }

    public function restoreNotif()
    {
        // if(!checkRoleList("MASTER.TAG.DELETE")){
        // 	return $this->response->setJSON([
        // 		'status' => 403,
        //         'message' => "Sorry, You don't have access",
        // 		'data' => []
        // 	], 403);
        // }

        $notifModel = new NotificationModel();
        $notificationId = $this->request->getVar("notificationId");
        if ($notificationId != '') {
            $notifModel->update($notificationId, ["deletedAt" => null]);

            return $this->response->setJson([
                'status' => 200,
                'message' => "Success Restore Notification",
                'data' => []
            ], 200);
        } else {
            return $this->response->setJson([
                'status' => 400,
                'message' => "Data is Not Valid (NotificationID)",
                'data' => []
            ], 400);
        }
    }
}
