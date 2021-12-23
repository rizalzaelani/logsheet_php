<?php

namespace App\Controllers\Customers;

use App\Controllers\BaseController;
use App\Models\Wizard\KledoModel;
use App\Models\Wizard\PackageModel;
use App\Models\Wizard\PackagePriceModel;
use App\Models\Wizard\SubscriptionModel;
use App\Models\Wizard\TransactionModel;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

class CustomersTransaction extends BaseController
{
    public function index()
    {
        // if (!checkRoleList("DASHBOARD.VIEW")) {
        // 	return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
        // }

        $subscriptionModel  = new SubscriptionModel();
        $transactionModel   = new TransactionModel();
        $packageModel       = new PackageModel();
        $kledoModel         = new KledoModel();
        $adminId = $this->session->get('adminId');

        $transaction        = $transactionModel->getAll();

        $data = array(
            'title'     => "Transaction",
            'subtitle'  => 'Transaction'
        );
        $data["breadcrumbs"] = [
            [
                "title" => "Home",
                "link"  => "Dashboard"
            ],
            [
                "title" => "Customers Transaction",
                "link"  => "CustomersTransaction"
            ]
        ];
        $data['transaction'] = $transaction;
        return $this->template->render('Customers/Transaction/index', $data);
    }

    public function datatable()
    {
        $request = \Config\Services::request();

        // if (!checkRoleList("MASTER.ASSET.VIEW")) {
        // 	echo json_encode(array(
        // 		"draw" => $request->getPost('draw'),
        // 		"recordsTotal" => 0,
        // 		"recordsFiltered" => 0,
        // 		"data" => [],
        // 		'status' => 403,
        // 		'message' => "You don't have access to this page"
        // 	));
        // }

        $table = 'vw_transaction';
        $column_order = array('refNumber', 'description', 'issueDate', 'dueDate', 'paidDate', 'paymentTotal');
        $column_search = array('refNumber', 'description', 'issueDate', 'dueDate', 'paidDate', 'paymentTotal');
        $order = array('createdAt' => 'desc');
        $DTModel = new \App\Models\Wizard\DatatableWizardModel($table, $column_order, $column_search, $order);

        $filtTag = explode(",", $_POST["columns"][2]["search"]["value"] ?? '');
        $filtLoc = explode(",", $_POST["columns"][3]["search"]["value"] ?? '');
        $where = [
            // 'userId' => $this->session->get("adminId"),
            'deletedAt' => null,
            // "(concat(',', tagName, ',') IN concat(',', " . $filtTag . ", ',') OR concat(',', tagLocationName, ',') IN concat(',', " . $filtLoc . ", ','))" => null
        ];
        $list = $DTModel->datatable($where);
        $output = array(
            "draw" => $request->getPost('draw'),
            "recordsTotal" => $DTModel->count_all($where),
            "recordsFiltered" => $DTModel->count_filtered($where),
            "data" => $list,
            'status' => 200,
            'message' => 'success'
        );
        echo json_encode($output);
    }

    public function detail($trxId)
    {
        $transactionModel   = new TransactionModel();
        $packageModel       = new PackageModel();
        $packagePriceModel  = new PackagePriceModel();
        $kledoModel         = new KledoModel();

        $transaction        = $transactionModel->getById($trxId);
        $packageId          = $transaction[0]['packageId'];
        $packagePriceId     = $transaction[0]['packagePriceId'];
        $package            = $packageModel->getById($packageId);
        $packagePrice       = $packagePriceModel->getById(['packagePriceId' => $packagePriceId]);

        $body = [
            'search' => $transaction[0]['refNumber']
        ];

        $getInvoice = $kledoModel->getInvoice(http_build_query($body, ""));
        $contact = "";
        if ($getInvoice['error'] == false) {
            $data = json_decode($getInvoice['data']);
            $contact = $data->data->data[0]->contact;
        }

        $data = array(
            'title'     => "Detail Transaction",
            'subtitle'  => 'Detail Transaction'
        );
        $data["breadcrumbs"] = [
            [
                "title" => "Home",
                "link"  => "Dashboard"
            ],
            [
                "title" => "Customers Transaction",
                "link"  => "CustomersTransaction"
            ],
            [
                "title" => "Detail Transaction",
                "link"  => "detail"
            ]
        ];
        $data['transaction']    = $transaction;
        $data['package']        = $package;
        $data['packagePrice']   = $packagePrice;
        $data['contact']        = $contact;
        return $this->template->render('Customers/Transaction/detail', $data);
        die();
    }

    public function approve()
    {
        try {
            $packageModel       = new PackageModel();
            $packagePriceModel  = new PackagePriceModel();
            $transactionModel   = new TransactionModel();
            $subscriptionModel  = new SubscriptionModel();

            $post = $this->request->getPost();
            $approveDate = $post['approveDate'];
            $transactionId = $post['transactionId'];
            $subscriptionId = $post['subscriptionId'];

            $dataTransaction = $transactionModel->getById($transactionId);
            $dataSubscription = $subscriptionModel->getById($subscriptionId);
            $packageId = $dataTransaction[0]['packageId'];
            $packagePriceId = $dataTransaction[0]['packagePriceId'];

            $dataPackage = $packageModel->getById($packageId);
            $dataPackagePrice = $packagePriceModel->getById(['packagePriceId' => $packagePriceId]);

            $date1 = new DateTime($approveDate);
            $bodyTransaction = [
                'paidDate' => $date1->format("Y-m-d H:i:s"),
                'approvedDate' => $date1->format("Y-m-d H:i:s")
            ];
            $transactionModel->update($transactionId, $bodyTransaction);

            // check renew or upgrade
            $checkString = strpos($dataTransaction[0]['description'], 'Renew');
            $bodySubscription = [
                'packageId'     => $packageId,
                'packagePriceId' => $packagePriceId,
                'period'        => $dataTransaction[0]['period'],
                'assetMax'      => $dataPackage[0]['assetMax'],
                'parameterMax'  => $dataPackage[0]['parameterMax'],
                'tagMax'        => $dataPackage[0]['tagMax'],
                'trxDailyMax'   => $dataPackage[0]['trxDailyMax'],
                'userMax'       => $dataPackage[0]['userMax'],
                'activeFrom'    => $checkString !== false ? $dataSubscription['activeFrom'] : $dataTransaction[0]['activeFrom'],
                // 'activeFrom'    => $dataTransaction[0]['activeFrom'],
                'expiredDate'   => $dataTransaction[0]['activeTo'],
            ];
            $subscriptionModel->update($subscriptionId, $bodySubscription);

            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Successfully approve payment',
                'data' => ''
            ));
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }

    public function delete()
    {
        $transactionModel = new TransactionModel();
        $post = $this->request->getPost();
        $transactionId = $post['transactionId'];
        $dataTransaction = $transactionModel->getById($transactionId);

        if ($dataTransaction[0]['paidDate'] == null && $dataTransaction[0]['approvedDate'] == null) {
            $date = new DateTime();
            $data = [
                'cancelDate' => $date->format("Y-m-d H:i:s")
            ];
            $transactionModel->update($transactionId, $data);

            return $this->response->setJSON(array(
                'status' => 200,
                'message' => 'Successfully delete transaction',
                'data' => ''
            ));
        } else {
            return $this->response->setJSON(array(
                'status' => 500,
                'message' => 'Bad Request!',
                'data' => ''
            ));
        }
    }
}
