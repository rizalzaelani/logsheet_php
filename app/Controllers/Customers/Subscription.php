<?php

namespace App\Controllers\Customers;

use App\Controllers\BaseController;
use App\Models\Wizard\kledoModel;
use App\Models\Wizard\PackageModel;
use App\Models\Wizard\PackagePriceModel;
use App\Models\Wizard\SubscriptionModel;
use App\Models\Wizard\TransactionModel;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

class Subscription extends BaseController
{
    public function index()
    {
        // if (!checkRoleList("DASHBOARD.VIEW")) {
        // 	return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
        // }

        $subscriptionModel  = new SubscriptionModel();
        $transactionModel   = new TransactionModel();
        $packageModel       = new PackageModel();
        $kledoModel         = new kledoModel();

        $adminId = $this->session->get('adminId');
        $dataSubscription   = $subscriptionModel->getByUser($adminId);
        $getSubscription = $subscriptionModel->getAllData(['userId' => $adminId, 'cancelDate' => null]);
        $getTransaction    = $transactionModel->getByUser(['userId' => $adminId, 'cancelDate' => null]);
        // d($transaction);
        // die();
        // $subscription = "";
        $transaction = "";

        $dataGet = [
            'search' => $this->session->get('name')
        ];

        $getInvoice = $kledoModel->getInvoice(http_build_query($dataGet, ""));
        $dataInvoice = "";
        if ($getInvoice['error'] == false) {
            $dataInvoice = json_decode($getInvoice['data'], TRUE)['data']['data'];
        }

        // if (count($getSubscription)) {
        //     $due = $getSubscription[0]['dueDate'];
        //     $now = new DateTime();
        //     $check = $now->format("Y-m-d H:i:s") < $due;

        //     if (!$check) {
        //         $transactionId = $getSubscription[0]['transactionId'];
        //         $duedate = [
        //             'cancelDate' => $getSubscription[0]['dueDate']
        //         ];
        //         if ($getSubscription[0]['paidDate'] == null && $getSubscription[0]['approvedDate'] == null) {
        //             $transactionModel->update($transactionId, $duedate);
        //         }
        //         $subscription = $subscriptionModel->getAllData(['userId' => $adminId, 'cancelDate' => null]);
        //         foreach ($subscription as $key => $value) {
        //             foreach ($dataInvoice as $i => $val) {
        //                 if ($value['invoiceId'] == $val['id']) {
        //                     $subscription[$key]['status'] = $val['status_id'];
        //                 }
        //             }
        //         }
        //     } else {
        //         $subscription = $getSubscription;
        //         foreach ($subscription as $key => $value) {
        //             foreach ($dataInvoice as $i => $val) {
        //                 if ($value['invoiceId'] == $val['id']) {
        //                     $subscription[$key]['status'] = $val['status_id'];
        //                 }
        //             }
        //         }
        //     }
        // }
        if (count($getTransaction)) {
            $due = $getTransaction[0]['dueDate'];
            $now = new DateTime();
            $check = $now->format("Y-m-d H:i:s") < $due;

            if (!$check) {
                $transactionId = $getTransaction[0]['transactionId'];
                $duedate = [
                    'cancelDate' => $getTransaction[0]['dueDate']
                ];
                if ($getTransaction[0]['paidDate'] == null && $getTransaction[0]['approvedDate'] == null) {
                    $transactionModel->update($transactionId, $duedate);
                }
                $transaction = $subscriptionModel->getAllData(['userId' => $adminId, 'cancelDate' => null]);
                foreach ($transaction as $key => $value) {
                    foreach ($dataInvoice as $i => $val) {
                        if ($value['invoiceId'] == $val['id']) {
                            $transaction[$key]['status'] = $val['status_id'];
                        }
                    }
                }
            } else {
                $transaction = $getTransaction;
                foreach ($transaction as $key => $value) {
                    foreach ($dataInvoice as $i => $val) {
                        if ($value['invoiceId'] == $val['id']) {
                            $transaction[$key]['status'] = $val['status_id'];
                        }
                    }
                }
            }
        }


        $data = array(
            'title' => "Subscription",
            'subtitle' => 'Subscription'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ],
            [
                "title" => "Subscription",
                "link"  => "Subscription"
            ]
        ];

        $package = "";
        if (count($dataSubscription)) {
            $packageId = $dataSubscription[0]['packageId'];
            $packageData = $packageModel->getById($packageId);
            $package = $packageData;
        }
        // if ($subscription) {
        //     $packageId = $subscription[0]['packageId'];
        //     $packageData = $packageModel->getById($packageId);
        //     $package = $packageData;
        // }
        // d($transaction);
        // die();
        $data['transaction'] = $transaction;
        $data['subscription'] = $dataSubscription;
        $data['package'] = $subscription = '' ? '' : $package[0]['name'];
        return $this->template->render('Customers/Subscription/index', $data);
    }

    public function datatable()
    {
        $request = \Config\Services::request();
        // if (!checkRoleList("MASTER.TAG.VIEW")) {
        //     echo json_encode(array(
        //         "draw" => $request->getPost('draw'),
        //         "recordsTotal" => 0,
        //         "recordsFiltered" => 0,
        //         "data" => [],
        //         'status' => 403,
        //         'message' => "You don't have access to this page"
        //     ));
        // }

        $table = "vw_subscription";
        $column_order = array('subscriptionId', 'packageId', 'packagePriceId', 'userId', 'period', 'assetMax', 'parameterMax', 'tagMax', 'trxDailyMax', 'userMax', 'activeFrom', 'expiredDate', 'createdAt', 'deletedAt');
        $column_search = array('subscriptionId', 'packageId', 'packagePriceId', 'userId', 'period', 'assetMax', 'parameterMax', 'tagMax', 'trxDailyMax', 'userMax', 'activeFrom', 'expiredDate', 'createdAt', 'deletedAt');
        $order = array('createdAt' => 'asc');
        $DTModel = new \App\Models\Wizard\DatatableWizardModel($table, $column_order, $column_search, $order);
        $where = [
            'userId' => $this->session->get("adminId"),
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

    public function upgrade()
    {
        $packageModel       = new PackageModel();
        $packagePriceModel  = new PackagePriceModel();
        $transactionModel   = new TransactionModel();

        $package = $packageModel->getAllPackage();
        $packagePrice = $packagePriceModel->getAll();
        $adminId = $this->session->get('adminId');
        $transaction    = $transactionModel->getAll(['userId' => $adminId, 'cancelDate' => null]);

        if ($transaction[0]['paidDate'] == null) {
            return View('errors/customError', ['errorCode' => 500, 'errorMessage' => "Please make a payment first or cancel the previous transaction"]);
        }

        $data = array(
            'title' => "Upgrade",
            'subtitle' => 'Upgrade'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ]
        ];
        $data['package'] = $package;
        $data['packagePrice'] = $packagePrice;
        return $this->template->render('Customers/Subscription/upgrade', $data);
    }

    public function renew()
    {

        $packageModel       = new PackageModel();
        $packagePriceModel  = new PackagePriceModel();
        $subscriptionModel  = new SubscriptionModel();
        $transactionModel   = new TransactionModel();

        $adminId        = $this->session->get('adminId');

        $dataSubscription   = $subscriptionModel->getByUser($adminId);
        $packageId          = $dataSubscription[0]['packageId'];
        $packagePriceId     = $dataSubscription[0]['packagePriceId'];
        $transaction        = $transactionModel->getAll(['userId' => $adminId, 'cancelDate' => null]);

        if ($transaction[0]['paymentTotal'] == '0') {
            return View('errors/customError', ['errorCode' => 500, 'errorMessage' => "You can only upgrade this package"]);
        }

        $packagePrice   = $packagePriceModel->getAll();


        $package        = $packageModel->getAllPackage(['packageId' => $packageId]);
        $packagePrice   = $packagePriceModel->getById(['packageId' => $packageId]);

        $data = array(
            'title'     => "Renewal",
            'subtitle'  => 'Renewal'
        );
        $data["breadcrumbs"] = [
            [
                "title" => "Home",
                "link"  => "Dashboard"
            ]
        ];
        $data['package']        = $package;
        $data['packagePrice']   = $packagePrice;
        return $this->template->render('Customers/Subscription/renew', $data);
    }

    public function invoiceUpgrade()
    {
        $subscriptionModel = new SubscriptionModel();
        $transactionModel = new TransactionModel();
        $kledoModel = new kledoModel();

        $adminId    = $this->session->get('adminId');
        $name       = $this->session->get('name');
        $post       = $this->request->getPost();
        $package    = json_decode($post['package']);

        try {
            //Get Product
            $bodyProduct = [
                'search' => 'Logsheet Digital'
            ];
            $getProduct = $kledoModel->getProduct(http_build_query($bodyProduct, ""));
            $product    = "";
            if ($getProduct['error'] == false) {
                $product = json_decode($getProduct['data'], TRUE)['data']['data'][0];
            }

            // Get Contact
            $bodyContact = [
                'search' => $name
            ];
            $getContact = $kledoModel->getContact(http_build_query($bodyContact, ""));
            $contact = "";
            if ($getContact['error'] == false) {
                $contact = json_decode($getContact['data'], TRUE)['data']['data'][0];
            }

            // Add Invoice
            $date = new DateTime();
            $bodyInvoice = [
                'trans_date'    => date("Y-m-d"),
                'due_date'      => $date->modify("+1 days")->format("Y-m-d"),
                'contact_id'    => $contact['id'],
                'status_id'     => 1,
                "include_tax"   => 0,
                'term_id'       => 1,
                'ref_number'    => "INVLD" . $contact['id'] . time(),
                'memo'          => $package->description,
                'attachment'    => ["INVLD" . $contact['id'] . $product['id'] . $package->description .  time()],
                'items'         => [[
                    'finance_account_id' => $product['id'],
                    'tax_id'            => null,
                    'desc'              => $package->description,
                    'qty'               => 1,
                    'price'             => $package->price,
                    'amount'            => $package->price,
                    'price_after_tax'   => 0,
                    'amount_after_tax'  => 0,
                    'tax_manual'        => 0,
                    'discount_percent'  => 0,
                    'unit_id'           => 2
                ]]
            ];
            $addInvoice = $kledoModel->addInvoice(json_encode($bodyInvoice));
            $dataInvoice = "";
            if ($addInvoice['error'] == false) {
                $dataInvoice = json_decode($addInvoice['data'], true)['data'];
            }

            $getSubscription = $subscriptionModel->getByUser($adminId);
            $subscription = "";
            if (count($getSubscription)) {
                $subscription = $getSubscription[0];
            }

            $nowDate = new DateTime();
            $expDate = new DateTime();
            $dd     = new DateTime();
            $transaction = "";
            if ($dataInvoice != "") {
                $transaction = [
                    'transactionId' => null,
                    'subscriptionId' => $subscription['subscriptionId'],
                    'userId'        => $adminId,
                    'invoiceId'     => $dataInvoice['id'],
                    'refNumber'     => $dataInvoice['ref_number'],
                    'period'        => $package->period,
                    'description'   => $package->description,
                    'paymentTotal'  => $package->price,
                    'paymentMethod' => 'Bank Transfer',
                    'attachment'    => null,
                    'issueDate'     => date("Y-m-d H:i:s"),
                    'dueDate'       => $dd->modify("+1 days")->format("Y-m-d H:i:s"),
                    'paidDate'      => null,
                    'approveDate'   => null,
                    'cancelDate'    => null,
                    'activeFrom'    => $nowDate->format("Y-m-d 00:00:00"),
                    'activeTo'      => $expDate->modify("+" . str_replace("-", " ", $package->period))->format("Y-m-d 23:59:59")
                ];
                $transactionModel->insert($transaction);
            }



            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Success add invoice',
                'data' => []
            ], 200);

            // $data = array(
            //     'title' => "Payment",
            //     'subtitle' => 'Payment'
            // );
            // $data["breadcrumbs"] = [
            //     [
            //         "title"    => "Home",
            //         "link"    => "Dashboard"
            //     ]
            // ];


            // return $this->template->render('Customers/Billing/payment', $data);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }

    public function payment()
    {
        $data = array(
            'title' => "Payment",
            'subtitle' => 'Payment'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ]
        ];


        return $this->template->render('Customers/Subscription/payment', $data);
    }

    public function update()
    {
        $transactionModel = new TransactionModel();
        $post = $this->request->getPost();
        $transactionId = $post['transactionId'];
        $dataTransaction = $transactionModel->getById($transactionId);
        $data = [
            'cancelDate' => $dataTransaction[0]['dueDate']
        ];
        if ($dataTransaction[0]['approvedDate'] == null && $dataTransaction[0]['cancelDate'] == null) {
            $transactionModel->update($transactionId, $data);
        }
        return $this->response->setJSON(array(
            'status' => 200,
            'message' => '',
            'data' => $post
        ));
    }

    public function cancelUp()
    {
        $transactionModel   = new TransactionModel();
        $transactionId = $this->request->getPost('transactionId');
        if ($transactionId == '') {
            return $this->response->setJSON(array(
                'status'    => 500,
                'message'   => 'Bad Request!',
                'data'      => ''
            ));
        }
        $date = new DateTime();
        $data = [
            'cancelDate' => $date->format("Y-m-d H:i:s")
        ];
        $transactionModel->update($transactionId, $data);
        return $this->response->setJSON(array(
            'status'    => 200,
            'message'   => 'Upgrade package cancelled',
            'data'      => ''
        ));
    }

    public function invoiceRenew()
    {
        $subscriptionModel  = new SubscriptionModel();
        $transactionModel   = new TransactionModel();
        $kledoModel         = new kledoModel();

        $adminId    = $this->session->get('adminId');
        $name       = $this->session->get('name');
        $post       = $this->request->getPost();
        $package    = json_decode($post['package']);

        try {
            //Get Product
            $bodyProduct = [
                'search' => 'Logsheet Digital'
            ];
            $getProduct = $kledoModel->getProduct(http_build_query($bodyProduct, ""));
            $product    = "";
            if ($getProduct['error'] == false) {
                $product = json_decode($getProduct['data'], TRUE)['data']['data'][0];
            }

            // Get Contact
            $bodyContact = [
                'search' => $name
            ];
            $getContact = $kledoModel->getContact(http_build_query($bodyContact, ""));
            $contact = "";
            if ($getContact['error'] == false) {
                $contact = json_decode($getContact['data'], TRUE)['data']['data'][0];
            }

            // Add Invoice
            $date = new DateTime();
            $bodyInvoice = [
                'trans_date'    => date("Y-m-d"),
                'due_date'      => $date->modify("+1 days")->format("Y-m-d"),
                'contact_id'    => $contact['id'],
                'status_id'     => 1,
                "include_tax"   => 0,
                'term_id'       => 1,
                'ref_number'    => "INVLD" . $contact['id'] . time(),
                'memo'          => $package->description,
                'attachment'    => ["INVLD" . $contact['id'] . $product['id'] . $package->description .  time()],
                'items'         => [[
                    'finance_account_id' => $product['id'],
                    'tax_id'            => null,
                    'desc'              => $package->description,
                    'qty'               => 1,
                    'price'             => $package->price,
                    'amount'            => $package->price,
                    'price_after_tax'   => 0,
                    'amount_after_tax'  => 0,
                    'tax_manual'        => 0,
                    'discount_percent'  => 0,
                    'unit_id'           => 2
                ]]
            ];
            $addInvoice = $kledoModel->addInvoice(json_encode($bodyInvoice));
            $dataInvoice = "";
            if ($addInvoice['error'] == false) {
                $dataInvoice = json_decode($addInvoice['data'], true)['data'];
            }

            $getSubscription = $subscriptionModel->getByUser($adminId);
            $subscription = "";
            if (count($getSubscription)) {
                $subscription = $getSubscription[0];
            }

            $nowDate = new DateTime($subscription['expiredDate']);
            $expDate = new DateTime();
            $dd     = new DateTime();
            $now    = new DateTime();
            $transaction = "";
            if ($dataInvoice != "") {
                $transaction = [
                    'transactionId' => null,
                    'subscriptionId' => $subscription['subscriptionId'],
                    'userId'        => $adminId,
                    'invoiceId'     => $dataInvoice['id'],
                    'refNumber'     => $dataInvoice['ref_number'],
                    'period'        => $package->period,
                    'description'   => $package->description,
                    'paymentTotal'  => $package->price,
                    'paymentMethod' => $package->price == '0' ? 'Free' : 'Bank Transfer',
                    'attachment'    => null,
                    'issueDate'     => date("Y-m-d H:i:s"),
                    'dueDate'       => $dd->modify("+1 days")->format("Y-m-d H:i:s"),
                    'paidDate'      => $package->price == '0' ? $now->format("Y-m-d H:i:s") : null,
                    'approvedDate'  => $package->price == '0' ? $now->format("Y-m-d H:i:s") : null,
                    'cancelDate'    => null,
                    'activeFrom'    => $nowDate->modify("+1 days")->format("Y-m-d 00:00:00"),
                    'activeTo'      => $nowDate->modify("+" . str_replace("-", " ", $package->period))->format("Y-m-d 23:59:59")
                ];
                $transactionModel->insert($transaction);
            }



            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Success add invoice',
                'data' => []
            ], 200);

            // $data = array(
            //     'title' => "Payment",
            //     'subtitle' => 'Payment'
            // );
            // $data["breadcrumbs"] = [
            //     [
            //         "title"    => "Home",
            //         "link"    => "Dashboard"
            //     ]
            // ];


            // return $this->template->render('Customers/Billing/payment', $data);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }
}
