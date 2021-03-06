<?php

namespace App\Controllers\Wizard;

use App\Controllers\BaseController;
use App\Models\Wizard\KledoModel;
use App\Models\Wizard\PackageGroupModel as WizardPackageGroupModel;
use App\Models\Wizard\PackageModel as WizardPackageModel;
use App\Models\Wizard\PackagePriceModel as WizardPackagePriceModel;
use App\Models\Wizard\SubscriptionModel as WizardSubscriptionModel;
use App\Models\Wizard\TransactionModel;
use DateTime;
use Exception;

class Wizard extends BaseController
{
    public function index()
    {
        $packageModel = new WizardPackageModel();
        $packageGroupModel = new WizardPackageGroupModel();
        $packagePriceModel = new WizardPackagePriceModel();
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

    public function getInvoice()
    {
        $packageModel = new WizardPackageModel();
        $packagePriceModel = new WizardPackagePriceModel();
        $subscriptionModell = new WizardSubscriptionModel();
        $transactionModel = new TransactionModel();
        $kledoModel = new KledoModel();

        $adminId = $this->session->get('adminId');

        $post = $this->request->getPost();
        // if ($post['package'] == "" || $post['fullName'] == "" || $post['companyName'] == "" || $post['email'] == "" || $post['phoneNumber'] == "") {
        //     return $this->response->setJSON(array(
        //         'status' => 500,
        //         'message' => 'Bad Request!'
        //     ), 500);
        // }
        try {
            $dateTime = new DateTime();
            $clone = new DateTime();

            $packageId = $post['package'];
            $packagePriceId = $post['packagePrice'];

            $package = $packageModel->getById($packageId);
            $packagePrice = $packagePriceModel->getByIdPrice($packagePriceId);

            $period = strtolower(str_replace("-", " ", $packagePrice['period']));
            $activeFrom = $dateTime->modify("+1 days")->format("Y-m-d 00:00:00");
            $expiredDate = $clone->modify("+" . $period)->format("Y-m-d 23:59:59");

            // var_dump($package);
            // var_dump($packagePrice);
            // die();

            $product = [];
            if ($package) {

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

                // Contact
                $bodyAddContact = [
                    'name'                  => $post['fullName'],
                    'company'               => $post['companyName'],
                    'address'               => $post['address'],
                    'phone'                 => $post['phoneNumber'],
                    'email'                 => $post['email'],
                    'type_id'               => 3,
                    'shipping_address'      => $post['address'],
                    'receivable_account_id' => 21,
                    'payable_account_id'    => 81,
                    'group_id'              => null,
                    'npwp'                  => ""
                ];
                $addContactRes = $kledoModel->addContact(json_encode($bodyAddContact));
                $resContact = json_decode($addContactRes['data']);
                $contactId = null;
                if ($resContact->success) {
                    $contactId = $resContact->data->id;
                } else {
                    return $this->response->setJSON(array(
                        'status' => 500,
                        'message' => $resContact->message,
                        'data' => []
                    ));
                }

                // Add Invoice
                $date = new DateTime();
                $body = [
                    'trans_date'    => date("Y-m-d"),
                    'due_date'      => $date->modify("+1 days")->format("Y-m-d"),
                    'contact_id'    => $contactId,
                    'status_id'     => 1,
                    "include_tax"   => 0,
                    'term_id'       => 1,
                    'ref_number'    => "INVLD" . $contactId . time(),
                    'memo'          => $package[0]['description'],
                    'attachment'    => ["https://kledo-live-user.s3.ap-southeast-1.amazonaws.com/rizal.api.kledo.com/finance/invoice/attachment/temp/211123/113415/Approve%20_%20Non%20Approve.png"],
                    'items'         => [[
                        'finance_account_id' => $product['id'],
                        'tax_id'            => null,
                        'desc'              => $package[0]['description'],
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
                // create pdf invoice
                if ($resInvoice->success) {
                    $dataInvoice = $resInvoice->data;

                    $contact = $dataInvoice->contact;
                    $items = $dataInvoice->items[0];
                    $tax = $items->item_tax;
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
                            'product_name'      => $items->product->name,
                            'description'       => $items->desc,
                            'qty'               => $items->qty,
                            'price'             => $items->price,
                            'amount'            => $items->amount,
                            'discount_percent'  => $items->discount_percent,
                            'tax_percent'       => null,
                            'tax_manual'        => 0,
                            'tax_title'         => "",
                        ]],
                        'message'                       => $dataInvoice->memo,
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
                    $base64_decode = base64_decode($base64);
                    $path = '../public/download/pdf/';
                    $name = 'invoice' . time() . '.pdf';
                    file_put_contents($path . $name, $base64_decode);
                    header("Content-type: application/pdf");

                    $subsId = uuidv4();

                    $subscription = [
                        'subscriptionId' => $subsId,
                        'packageId'     => $packageId,
                        'packagePriceId' => $packagePriceId,
                        'userId'        => $adminId,
                        'period'        => $packagePrice['period'],
                        'assetMax'      => $package[0]['assetMax'],
                        'parameterMax'  => $package[0]['parameterMax'],
                        'tagMax'        => $package[0]['tagMax'],
                        'trxDailyMax'   => $package[0]['trxDailyMax'],
                        'userMax'       => $package[0]['userMax'],
                        'activeFrom'    => $activeFrom,
                        'expiredDate'   => $expiredDate
                    ];
                    $subscriptionModell->insert($subscription);

                    $dueDateTrx = new DateTime();
                    $transaction = [
                        'transactionId' => null,
                        'subscriptionId' => $subsId,
                        'packageId'     => $packageId,
                        'packagePriceId' => $packagePriceId,
                        'invoiceId'     => $dataInvoice->id,
                        'refNumber'     => $dataInvoice->ref_number,
                        'userId'        => $adminId,
                        'period'        => $packagePrice['period'],
                        'description'   => $package[0]['description'],
                        'paymentTotal'  => $packagePrice['price'],
                        // 'paymentMethod' => "-",
                        'attachment'    => null,
                        'issueDate'     => date("Y-m-d H:i:s"),
                        'dueDate'       => $dueDateTrx->modify("+1 days")->format("Y-m-d H:i:s"),
                        // 'approveDate'   => date("Y-m-d H:i:s"),
                        'cancelDate'    => null,
                        'activeFrom'    => $activeFrom,
                        'activeTo'      => $expiredDate
                    ];
                    if ($packagePrice['price'] == '0' || $packagePrice['price'] == 0) {
                        $transaction['paidDate']      = date("Y-m-d H:i:s");
                        $transaction['approvedDate']  = date("Y-m-d H:i:s");
                        $transaction['paymentMethod'] = 'free';
                    } else {
                        $transaction['paidDate']      = null;
                        $transaction['approvedDate']  = null;
                        $transaction['paymentMethod'] = 'Bank Transfer';
                    }
                    $transactionModel->insert($transaction);

                    // send email
                    $message = file_get_contents("assets/Mail/subscription.txt");
                    $transdate = date("Y-m-d H:i:s");
                    $refnumber = $dataInvoice->ref_number;
                    $transdesc = $package[0]['description'];
                    $transprice = $packagePrice['price'];
                    $transdiscount = '0%';
                    $transtax = '0';
                    $transtotal = $items->price;

                    // send email
                    $email = \Config\Services::email();

                    $message = str_replace("{{trans_date}}", $transdate, $message);
                    $message = str_replace("{{ref_number}}", $refnumber, $message);
                    $message = str_replace("{{trans_desc}}", $transdesc, $message);
                    $message = str_replace("{{trans_price}}", 'Rp. ' . number_format($transprice), $message);
                    $message = str_replace("{{trans_discount}}", $transdiscount, $message);
                    $message = str_replace("{{trans_tax}}", 'Rp. ' . number_format($transtax), $message);
                    $message = str_replace("{{trans_total}}", 'Rp. ' . number_format($transtotal), $message);

                    $subject = 'Invoice for order #' . $dataInvoice->ref_number;
                    $email->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
                    $email->setTo($contact->email);
                    $email->setSubject($subject);
                    $email->setMessage($message);
                    $email->attach($path . $name);
                    $email->setMailType("html");
                    $email->send();
                    $email->printDebugger(['headers']);
                    unlink($path . $name);

                    return $this->response->setJSON(array(
                        'status' => 200,
                        'message' => "Successfully register the application. You'll be redirected to the invoice page.",
                        'data' => $dataInvoice,
                    ));
                } else {
                    return $this->response->setJSON(array(
                        'status' => 500,
                        'message' => $resInvoice->message,
                        'data' => '[]'
                    ));
                }
            }
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }

    public function invoice($invoiceId)
    {
        $kledoModel = new KledoModel();

        $data = [
            'title' => "Invoice",
        ];

        $bodyInvoice = [
            'search' => $invoiceId
        ];
        $resInvoice = $kledoModel->getInvoice(http_build_query($bodyInvoice, ""));
        $invoice = "";
        if ($resInvoice['error'] == false) {
            $dataInvoice = json_decode($resInvoice['data']);
            if ($dataInvoice->success) {
                $invoice = $dataInvoice->data->data;
            }
        }
        $data['invoice'] = $invoice;

        return $this->template->render('Wizard/invoice', $data);
    }

    public function download()
    {
        $kledoModel = new KledoModel();
        $email = \Config\Services::email();

        $post = $this->request->getPost('invoice');
        $data = json_decode($post)->data;
        $contact = $data->contact;
        $items = $data->items[0];
        $tax = $items->item_tax;
        $bodyGenerate = [
            'ref_number'        => $data->ref_number,
            'trans_date'        => $data->trans_date,
            'due_date'          => $data->due_date,
            'contact_name'      => $contact->name,
            'contact_address'   => $contact->address,
            'contact_phone'     => $contact->phone,
            'contact_email'     => $contact->email,
            'company_name'      => 'Nocola IOT Solution',
            'company_address'   => 'Jl. Ir. H. juanda No. 117',
            'company_phone'     => '02187767777',
            'company_email'     => 'rizal@nocola.co.id',
            'items' => [[
                'product_name'      => $items->product->name,
                'description'       => $items->desc,
                'qty'               => $items->qty,
                'price'             => $items->price,
                'amount'            => $items->amount,
                'discount_percent'  => $items->discount_percent,
                'tax_percent'       => null,
                'tax_manual'        => 0,
                'tax_title'         => "",
            ]],
            'message'                       => $data->memo,
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
}
