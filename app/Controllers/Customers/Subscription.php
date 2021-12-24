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
        $kledoModel         = new KledoModel();
        $packagePriceModel  = new PackagePriceModel();

        $adminId = $this->session->get('adminId');
        $dataSubscription   = $subscriptionModel->getByUser($adminId);
        $subscription = $subscriptionModel->getAllData(['userId' => $adminId, 'cancelDate' => null]);
        $getTransaction    = $transactionModel->getByUser(['userId' => $adminId, 'cancelDate' => null]);

        $transaction = "";

        $dataGet = [
            'search' => $this->session->get('name')
        ];

        $getInvoice = $kledoModel->getInvoice(http_build_query($dataGet, ""));
        $dataInvoice = "";
        if ($getInvoice['error'] == false) {
            $dataInvoice = json_decode($getInvoice['data'], TRUE)['data']['data'];
        }
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
                $transaction = $transactionModel->getByUser(['userId' => $adminId, 'cancelDate' => null]);
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
        }else{
            $dateTime   = new DateTime();
            $clone      = new DateTime();

            $packageName = 'free';
            $getPackage = $packageModel->getByName(['name' => $packageName]);
            $packageData = $getPackage[0];
            $getPackagePrice = $packagePriceModel->getById(['packageId' => $packageData['packageId']]);
            $packagePrice = $getPackagePrice[0];
            $packagePriceId = $packagePrice['packagePriceId'];

            $bodyCheckContact = [
                'search' => $this->session->get('name')
            ];
            $checkContact = $kledoModel->getContact(http_build_query($bodyCheckContact));
            $contact = "";
            if (!$checkContact['error']) {
                $resData = json_decode($checkContact['data']);
                if (count($resData->data->data)) {
                    $contact = $resData->data->data[0];
                }else{
                    // add contact to kledo
                    $parameter = json_decode($this->session->get('parameter'));
                    $address = $parameter->city . ' ' . $parameter->postalCode . ', ' . $parameter->country;
                    $bodyAddContact = [
                        'name'                  => $this->session->get('name'),
                        'company'               => $parameter->company,
                        'address'               => $address,
                        'phone'                 => $parameter->noTelp,
                        'email'                 => $this->session->get('email'),
                        'type_id'               => 3,
                        'shipping_address'      => $address,
                        'receivable_account_id' => 21,
                        'payable_account_id'    => 81,
                        'group_id'              => null,
                        'npwp'                  => ""
                    ];
                    $addContactRes = $kledoModel->addContact(json_encode($bodyAddContact));
                    $resContact = json_decode($addContactRes['data']);
                    $contact = $resContact->data;
                }
            }

            // get product from kledo
            $bodyProduct = [
                'search' => 'Logsheet Digital'
            ];
            $resGetProduct = $kledoModel->getProduct(http_build_query($bodyProduct, ""));
            if ($resGetProduct['error'] == false) {
                $resData = json_decode($resGetProduct['data'], true);
                if ($resData['success']) {
                    $product = $resData['data']['data'][0];
                }
            }


            // create invoice to kledo
            $dueDateInv = new DateTime();
            $body = [
                'trans_date'    => date("Y-m-d"),
                'due_date'      => $dueDateInv->modify("+1 days")->format("Y-m-d"),
                'contact_id'    => $contact->id,
                'status_id'     => 1,
                "include_tax"   => 0,
                'term_id'       => 1,
                'ref_number'    => "INVLD" . $contact->id . time(),
                'memo'          => $packageData['description'],
                'attachment'    => ["https://kledo-live-user.s3.ap-southeast-1.amazonaws.com/rizal.api.kledo.com/finance/invoice/attachment/temp/211123/113415/Approve%20_%20Non%20Approve.png"],
                'items'         => [[
                    'finance_account_id' => $product['id'],
                    'tax_id'            => null,
                    'desc'              => $packageData['description'],
                    'qty'               => 1,
                    'price'             => $packagePrice['price'],
                    'amount'            => $packagePrice['price'],
                    'price_after_tax'   => 0,
                    'amount_after_tax'  => 0,
                    'tax_manual'        => 0,
                    'discount_percent'  => 0,
                    'unit_id'           => 2
                ]]
            ];
            $addInvoiceRes =  $kledoModel->addInvoice(json_encode($body));
            $resInvoice = json_decode($addInvoiceRes['data']);
            $dataInvoice = "";
            if ($resInvoice->success) {
                $dataInvoice = $resInvoice->data;
            }

            $activeFrom = $dateTime->format("Y-m-d H:i:s");
            $expiredDate = $clone->modify("+1 Month")->format("Y-m-d H:i:s");
            $subsId = uuidv4();
            $bodySubscription = [
                'subscriptionId' => $subsId,
                'packageId'     => $packageData['packageId'],
                'packagePriceId' => $packagePriceId,
                'userId'        => $this->session->get('adminId'),
                'period'        => $packagePrice['period'],
                'assetMax'      => $packageData['assetMax'],
                'parameterMax'  => $packageData['parameterMax'],
                'tagMax'        => $packageData['tagMax'],
                'trxDailyMax'   => $packageData['trxDailyMax'],
                'userMax'       => $packageData['userMax'],
                'activeFrom'    => $activeFrom,
                'expiredDate'   => $expiredDate
            ];
            $subscriptionModel->insert($bodySubscription);
            $dueDateTrx = new DateTime();
            $transaction = [
                'transactionId' => null,
                'subscriptionId' => $subsId,
                'packageId'     => $packageData['packageId'],
                'packagePriceId' => $packagePriceId,
                'invoiceId'     => $dataInvoice->id,
                'refNumber'     => $dataInvoice->ref_number,
                'userId'        => $this->session->get('adminId'),
                'period'        => $packagePrice['period'],
                'description'   => $packageData['description'],
                'paymentTotal'  => $packagePrice['price'],
                'paymentMethod' => "free",
                'attachment'    => 'free',
                'issueDate'     => date("Y-m-d H:i:s"),
                'dueDate'       => $dueDateTrx->modify("+1 days")->format("Y-m-d H:i:s"),
                'paidDate'      => date("Y-m-d H:i:s"),
                'approvedDate'  => date("Y-m-d H:i:s"),
                'cancelDate'    => null,
                'activeFrom'    => $activeFrom,
                'activeTo'      => $expiredDate
            ];
            $transactionModel->insert($transaction);

            $subsData       = $subscriptionModel->getByUser($adminId);
            $packageSubsId  = $subsData[0]['packageId'];
            $dataPackage    = $packageModel->getById($packageSubsId);
            $package        = $dataPackage;

            $dataSubscription   = $subscriptionModel->getByUser($adminId);
            $transaction     = $transactionModel->getByUser(['userId' => $adminId, 'cancelDate' => null]);
        }

        $data['transaction'] = $transaction;
        $data['subscription'] = $dataSubscription;
        $data['package'] = $subscription == '' ? '' : $package[0]['name'];
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
        $transaction    = $transactionModel->getByUser(['userId' => $adminId, 'cancelDate' => null]);

        if ($transaction[0]['paidDate'] == null && $transaction[0]['cancelDate'] == null) {
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
            ],
            [
                "title" => "Subscription",
                "link"  => "Subscription"
            ],
            [
                "title" => "Upgrade",
                "link"  => "Upgrade"
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
        $transaction        = $transactionModel->getByUser(['userId' => $adminId, 'cancelDate' => null]);

        if ($transaction[0]['paymentTotal'] == '0') {
            return View('errors/customError', ['errorCode' => 500, 'errorMessage' => "You can only upgrade this package"]);
        } else if ($transaction[0]['paidDate'] == null && $transaction[0]['cancelDate'] == null) {
            return View('errors/customError', ['errorCode' => 500, 'errorMessage' => "Please make a payment first or cancel the previous transaction"]);
        }

        $packagePrice   = $packagePriceModel->getAll();


        $package        = $packageModel->getAllPackage(['packageId' => $packageId]);
        $packagePrice   = $packagePriceModel->getById(['packageId' => $packageId]);

        $data = array(
            'title'     => "Renew",
            'subtitle'  => 'Renew'
        );
        $data["breadcrumbs"] = [
            [
                "title" => "Home",
                "link"  => "Dashboard"
            ],
            [
                "title" => "Subscription",
                "link"  => "Subscription"
            ],
            [
                "title" => "Renew",
                "link"  => "Renew"
            ]
        ];
        $data['package']        = $package;
        $data['packagePrice']   = $packagePrice;
        return $this->template->render('Customers/Subscription/renew', $data);
    }

    public function invoiceUpgrade()
    {
        $email = \Config\Services::email();

        $subscriptionModel = new SubscriptionModel();
        $transactionModel = new TransactionModel();
        $kledoModel = new KledoModel();

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
                    'price'             => $package->packagePrice->price,
                    'amount'            => $package->packagePrice->price,
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
                    'transactionId' => $package->transactionId,
                    'subscriptionId' => $subscription['subscriptionId'],
                    'packageId'     => $package->packageId,
                    'packagePriceId' => $package->packagePrice->packagePriceId,
                    'userId'        => $adminId,
                    'invoiceId'     => $dataInvoice['id'],
                    'refNumber'     => $dataInvoice['ref_number'],
                    'period'        => $package->period,
                    'description'   => 'Upgrade ' . $package->description,
                    'paymentTotal'  => $package->packagePrice->price,
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

            $message = file_get_contents(base_url() . "/assets/Mail/subscription.txt");
            $transdate = date("Y-m-d H:i:s");
            $refnumber = $dataInvoice['ref_number'];
            $transdesc = $transaction['description'];
            $transprice = $package->packagePrice->price;
            $transdiscount = '0%';
            $transtax = '0';
            $transtotal = $package->packagePrice->price;

            $message = str_replace("{{trans_date}}", $transdate, $message);
            $message = str_replace("{{ref_number}}", $refnumber, $message);
            $message = str_replace("{{trans_desc}}", $transdesc, $message);
            $message = str_replace("{{trans_price}}", 'Rp. ' . number_format($transprice), $message);
            $message = str_replace("{{trans_discount}}", $transdiscount, $message);
            $message = str_replace("{{trans_tax}}", 'Rp. ' . number_format($transtax), $message);
            $message = str_replace("{{trans_total}}", 'Rp. ' . number_format($transtotal), $message);

            $subject = 'Invoice for order #' . $dataInvoice['ref_number'];
            $email->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
            $email->setTo($this->session->get('email'));
            $email->setSubject($subject);
            $email->setMessage($message);
            $email->setMailType("html");
            $email->send();
            $email->printDebugger(['headers']);

            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Success add invoice',
                'data' => []
            ], 200);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }

    public function invoice($trxId)
    {
        $transactionModel   = new TransactionModel();
        $kledoModel         = new KledoModel();
        $adminId = $this->session->get('adminId');

        $dataTransaction    = $transactionModel->getByUser(['userId' => $adminId, 'transactionId' => $trxId]);

        $body = [
            'search' => $dataTransaction[0]['refNumber']
        ];

        $getInvoice = $kledoModel->getInvoice(http_build_query($body, ""));
        $contact = "";
        if ($getInvoice['error'] == false) {
            $data = json_decode($getInvoice['data']);
            $contact = $data->data->data[0]->contact;
        }

        $data = array(
            'title' => "Detail Invoice",
            'subtitle' => 'Detail Invoice'
        );
        $data["breadcrumbs"] = [
            [
                "title"    => "Home",
                "link"    => "Dashboard"
            ]
        ];
        $data['transaction'] = $dataTransaction;
        $data['contact']     = $contact;
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
            'message'   => 'Update package cancelled',
            'data'      => ''
        ));
    }

    public function invoiceRenew()
    {
        $email = \Config\Services::email();

        $subscriptionModel  = new SubscriptionModel();
        $transactionModel   = new TransactionModel();
        $kledoModel         = new KledoModel();

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
                    'transactionId' => $package->transactionId,
                    'subscriptionId' => $subscription['subscriptionId'],
                    'userId'        => $adminId,
                    'invoiceId'     => $dataInvoice['id'],
                    'packageId'     => $package->packageId,
                    'packagePriceId' => $package->packagePriceId,
                    'refNumber'     => $dataInvoice['ref_number'],
                    'period'        => $package->period,
                    'description'   => 'Renew ' . $package->description,
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

            $message = file_get_contents("assets/Mail/subscription.txt");
            $transdate = date("Y-m-d H:i:s");
            $refnumber = $dataInvoice['ref_number'];
            $transdesc = $transaction['description'];
            $transprice = $package->price;
            $transdiscount = '0%';
            $transtax = '0';
            $transtotal = $package->price;

            $message = str_replace("{{trans_date}}", $transdate, $message);
            $message = str_replace("{{ref_number}}", $refnumber, $message);
            $message = str_replace("{{trans_desc}}", $transdesc, $message);
            $message = str_replace("{{trans_price}}", 'Rp. ' . number_format($transprice), $message);
            $message = str_replace("{{trans_discount}}", $transdiscount, $message);
            $message = str_replace("{{trans_tax}}", 'Rp. ' . number_format($transtax), $message);
            $message = str_replace("{{trans_total}}", 'Rp. ' . number_format($transtotal), $message);

            $subject = 'Invoice for order #' . $dataInvoice['ref_number'];
            $email->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
            $email->setTo($this->session->get('email'));
            $email->setSubject($subject);
            $email->setMessage($message);
            $email->setMailType("html");
            $email->send();
            $email->printDebugger(['headers']);

            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Success add invoice',
                'data' => []
            ], 200);
            // return $this->template->render('Customers/Billing/payment', $data);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }

    public function downloadInvoice()
    {
        $kledoModel = new KledoModel();
        $post = $this->request->getPost('transaction');
        $transaction = json_decode($post, true);

        $bodyGetInvoice = [
            'search' => $transaction['refNumber']
        ];

        $getInvoice = $kledoModel->getInvoice(http_build_query($bodyGetInvoice, ""));
        $dataInvoice = "";
        if ($getInvoice['error'] == false) {
            $dataInvoice = json_decode($getInvoice['data'])->data->data[0];
        }
        $contact = $dataInvoice->contact;
        $bodyGenerate = [
            'ref_number'        => $dataInvoice->ref_number,
            'trans_date'        => $dataInvoice->trans_date,
            'due_date'          => $dataInvoice->due_date,
            'contact_name'      => $contact->name,
            'contact_address'   => $contact->address,
            'contact_phone'     => $contact->phone,
            'contact_email'     => $contact->email,
            'company_name'      => 'Nocola IOT Solution',
            'company_address'   => 'Jl. Ir. H. juanda No. 117',
            'company_phone'     => '02187767777',
            'company_email'     => 'rizal@nocola.co.id',
            'items' => [[
                'product_name'      => $dataInvoice->memo,
                'description'       => $dataInvoice->memo,
                'qty'               => 1,
                'price'             => $transaction['price'],
                'amount'            => $dataInvoice->amount_after_tax,
                'discount_percent'  => 0,
                'tax_percent'       => null,
                'tax_manual'        => 0,
                'tax_title'         => "",
            ]],
            // 'message'                       => $dataInvoice->memo,
            'company_logo'                  => "https://kledo-live-user.s3.ap-southeast-1.amazonaws.com/rizal.api.kledo.com/invoice-logo.png",
            'signature_name'                => 'Rizal Zaelani',
            'signature_dept'                => 'Finance Dept',
            'invoice_signature_url'         => "https://kledo-live-user.s3.ap-southeast-1.amazonaws.com/rizal.api.kledo.com/invoice-signature.png",
            'invoice_lang_product'          => 'Product',
            'invoice_lang_qty'              => 'Satuan',
            'invoice_lang_unit'             => 'Package',
            'invoice_lang_desc'             => 'Description',
            "invoice_lang_amount"           => "Harga",
            'invoice_lang_signature_header' => 'Dengan Hormat,',
            'invoice_lang_message'          => 'Description',
            'trans_type_title'              => 'Invoice',
        ];
        $resGenerate = $kledoModel->generateInvoice(json_encode($bodyGenerate));
        $base64 = base64_encode($resGenerate['data']);
        return $this->response->setJSON(array(
            'status' => 200,
            'message' => 'Successfully get file',
            'data' => $base64,
        ));
    }

    public function confirmation()
    {
        $transactionModel = new TransactionModel();

        $transactionId = $this->request->getPost('transactionId');
        $file = $this->request->getFile('attachment');

        try {
            $dirPath = 'upload/Subscription';
            $namePhoto = "";
            $dirPhoto = "";
            if ($file != null) {
                if (!is_dir($dirPath)) {
                    mkdir($dirPath);
                }
                $dirPhoto = $dirPath . '/' . 'file' . $this->session->get('adminId');
                if (!is_dir($dirPhoto)) {
                    mkdir($dirPhoto);
                }
                $namePhoto = 'TRX_' . $file->getRandomName();
                $image = \Config\Services::image()
                    ->withFile($file)
                    ->resize(480, 480, true, 'heigth')
                    ->save($dirPhoto . '/' . $namePhoto);
            }

            $dataTransaction = [
                'attachment' => base_url() . '/' . $dirPhoto . '/' . $namePhoto
            ];

            $transactionModel->update($transactionId, $dataTransaction);

            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Success Upload Attachment',
                'data' => ''
            ], 200);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }

        die();
    }
}
