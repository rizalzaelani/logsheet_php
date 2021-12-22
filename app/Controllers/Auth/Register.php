<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\USMAN\AppsModel;
use App\Models\USMAN\HttpRequest2Model;
use App\Models\Wizard\KledoModel;
use App\Models\Wizard\PackageModel;
use App\Models\Wizard\PackagePriceModel;
use App\Models\Wizard\SubscriptionModel;
use App\Models\Wizard\TransactionModel;
use DateTime;
use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;
use HTTP_Request2;
use HTTP_Request2_Exception;

class Register extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'Register Page | Logsheet Digital',
            'subtitle' => 'Logsheet Digital'
        );

        return $this->template->render('Auth/register', $data);
    }

    public function doRegister()
    {
        $isValid = $this->validate([
            'fullname' => 'required',
            'noTelp' => 'required',
            'email' => 'required',
            'password' => 'required',
            'company' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'country' => 'required',
        ]);

        if (!$isValid) {
            return $this->response->setJSON([
                'status' => 400,
                'error' => true,
                'message' => "Invalid Data",
                'data' => $this->validator->getErrors()
            ], 400);
        }

        $param["name"] = $this->request->getVar('appName');
        $param["code"] = str_replace(" ", "-", strtolower($param["name"]));
        $param["description"] = "-";

        $param['email'] = $this->request->getVar('email');
        $param['password'] = $this->request->getVar('password');
        $param['confirm_password'] = $this->request->getVar('password');
        $param['app_url'] = base_url("/");
        $param['app_group'] = "logsheet";

        $param['parameter[fullname]'] = $this->request->getVar('fullname');
        $param['parameter[noTelp]'] = $this->request->getVar('noTelp');
        $param['parameter[company]'] = $this->request->getVar('company');
        $param['parameter[city]'] = $this->request->getVar('city');
        $param['parameter[postalCode]'] = $this->request->getVar('postalCode');
        $param['parameter[country]'] = $this->request->getVar('country');
        $param['parameter[tag]'] = "";
        $param['parameter[tagLocation]'] = "";

        $param['group'] = "Superadmin";
        $param['role'] = '[{"code":"APPLICATION.ASSETSTATUS.DELETE","name":"DELETE","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.ASSETSTATUS.MODIFY","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.ASSETSTATUS.RESTORE","name":"RESTORE","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.MODIFY","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"APPLICATION.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"Application","type":"client"},{"code":"DASHBOARD.VIEW","name":"VIEW","description":null,"parent1":"DASHBOARD","parent2":null,"type":"client"},{"code":"FINDING.CLOSE","name":"CLOSE","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.DETAIL.LIST.VIEW","name":"LIST PARAMETER","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.DETAIL.VIEW","name":"DETAIL","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.LOG.ADD","name":"CREATE","description":null,"parent1":"FINDING","parent2":"TIMELINE","type":"client"},{"code":"FINDING.LOG.LIST","name":"VIEW","description":null,"parent1":"FINDING","parent2":"TIMELINE","type":"client"},{"code":"FINDING.OPEN","name":"OPEN","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"FINDING.VIEW","name":"VIEW","description":null,"parent1":"FINDING","parent2":null,"type":"client"},{"code":"LOGACTIVITY.VIEW","name":"VIEW","description":null,"parent1":"LOG ACTIVITY","parent2":null,"type":"client"},{"code":"MASTER.ASSET.ADD","name":"CREATE","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.DELETE","name":"REMOVE","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.DETAIL","name":"DETAIL","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.PARAMETER.IMPORT.SAMPLE","name":"IMPORT PARAMETER","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.PARAMETER.SORT","name":"SORTING PARAMETER","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.UPDATE","name":"MODIFY","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.ASSET.VIEW","name":"VIEW","description":null,"parent1":"ASSET","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.ADD","name":"CREATE","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.DELETE","name":"REMOVE","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.IMPORT","name":"IMPORT","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.UPDATE","name":"MODIFY","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAG.VIEW","name":"VIEW","description":null,"parent1":"TAG","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.ADD","name":"CREATE","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.DELETE","name":"REMOVE","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.DETAIL","name":"DETAIL","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.IMPORT","name":"IMPORT","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.UPDATE","name":"MODIFY","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"MASTER.TAGLOCATION.VIEW","name":"VIEW","description":null,"parent1":"TAG LOCATION","parent2":"MASTER","type":"client"},{"code":"NOTIFICATION.ADD","name":"CREATE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.DELETE","name":"DELETE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.MODIFY","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.MODIFY.STATUS","name":"PAUSE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.RESTORE","name":"RESTORE","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"NOTIFICATION.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"NOTIFICATION","type":"client"},{"code":"REPORT.ASSET.DETAIL","name":"DETAIL","description":null,"parent1":"ASSET","parent2":"REPORT","type":"client"},{"code":"REPORT.ASSET.VIEW","name":"VIEW","description":null,"parent1":"ASSET","parent2":"REPORT","type":"client"},{"code":"REPORT.RAWDATA.VIEW","name":"RAWDATA","description":null,"parent1":"REPORT","parent2":null,"type":"client"},{"code":"ROLE.ADD","name":"CREATE","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.DELETE","name":"DELETE","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.DETAIL.VIEW","name":"DETAIL","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.MODIFY","name":"MODIFY","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"ROLE.VIEW","name":"VIEW","description":null,"parent1":"USER MANAGEMENT","parent2":"ROLE","type":"client"},{"code":"SCHEDULE.ADD","name":"ADD","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"SCHEDULE.IMPORT","name":"IMPORT","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"SCHEDULE.LIST","name":"LIST","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"SCHEDULE.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"SCHEDULE","type":"client"},{"code":"TRX.APPROVE","name":"APPROVE","description":null,"parent1":"TRANSACTION","parent2":null,"type":"client"},{"code":"TRX.DETAIL.VIEW","name":"DETAIL","description":null,"parent1":"TRANSACTION","parent2":null,"type":"client"},{"code":"TRX.VIEW","name":"VIEW","description":null,"parent1":"TRANSACTION","parent2":null,"type":"client"},{"code":"USER.ADD","name":"CREATE","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"USER.DELETE","name":"DELETE","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"USER.MODIFY","name":"MODIFY","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"USER.VIEW","name":"VIEW","description":null,"parent1":"USER MANAGEMENT","parent2":"USER","type":"client"},{"code":"VERSIONAPPS.DELETE","name":"REMOVE","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.DETAIL","name":"DETAIL","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.DOWNLOAD","name":"DOWNLOAD","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.RELEASE","name":"RELEASE","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.UPDATE","name":"MODIFY","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"},{"code":"VERSIONAPPS.VIEW","name":"VIEW","description":null,"parent1":"SETTING","parent2":"VERSION APP","type":"client"}]';
        $param['roleGroup[Superadmin]'] = getenv("ROLELIST");

        try {
            $appModel           = new AppsModel();
            $packageModel       = new PackageModel();
            $packagePriceModel  = new PackagePriceModel();
            $kledoModel         = new KledoModel();
            $subscriptionModel  = new SubscriptionModel();
            $transactionModel   = new TransactionModel();
            $dataRes = $appModel->createApps($param);

            $data = $dataRes['data'];
            if ($dataRes['error']) {
                return $this->response->setJSON(array(
                    'status' => isset($data->message) ? 400 : 500,
                    'message' => $data->message ?? $dataRes['message'],
                    'data' => $data
                ), isset($data->message) ? 400 : 500);
            } else {
                $issuedAt   = new DateTimeImmutable();
                $expire     = $issuedAt->modify('+10 years')->getTimestamp();
                $serverName = getenv("DOMAIN_NAME");
                $jwtPayload = [
                    'iss'  => $serverName,                       // Issuer
                    'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
                    'nbf'  => $issuedAt->getTimestamp(),         // Not before
                    'exp'  => $expire,                           // Expire
                    'email' => $param["email"],
                    'data' => [
                        'email' => $param["email"],
                        'userId' => $data->data->userId,
                    ]
                ];

                $jwt = JWT::encode($jwtPayload, getenv("JWT_SECRET_KEY_MAIL_VERIFY"));

                $linkReset = site_url("verifyMail/") . $jwt;
                $message = file_get_contents(base_url() . "/assets/Mail/verifyMail.txt");

                $message = str_replace("{{linkBtn}}", $linkReset, $message);
                $message = str_replace("{{emailAddress}}", $param['email'], $message);

                $email = \Config\Services::email();

                $email->setFrom('logsheet-noreply@nocola.co.id', 'Logsheet Digital');
                $email->setTo($param['email']);
                $email->setSubject('Verify your Logsheet Digital Account');
                // $email->setMessage($message);
                $email->setMailType("html");

                $email->send();

                // add contact to kledo
                $address = $this->request->getVar('city') . ' ' . $this->request->getVar('postalCode') . ', ' . $this->request->getVar('country');
                $bodyAddContact = [
                    'name'                  => $data->data->user[0]->name,
                    'company'               => $this->request->getVar('company'),
                    'address'               => $address,
                    'phone'                 => $this->request->getVar('noTelp'),
                    'email'                 => $this->request->getVar('email'),
                    'type_id'               => 3,
                    'shipping_address'      => $address,
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

                $dateTime   = new DateTime();
                $clone      = new DateTime();
                $packageName = 'free';
                $getPackage = $packageModel->getByName(['name' => $packageName]);
                $package = $getPackage[0];
                $getPackagePrice = $packagePriceModel->getById(['packageId' => $package['packageId']]);
                $packagePrice = $getPackagePrice[0];
                $packagePriceId = $packagePrice['packagePriceId'];

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
                    'contact_id'    => $contactId,
                    'status_id'     => 1,
                    "include_tax"   => 0,
                    'term_id'       => 1,
                    'ref_number'    => "INVLD" . $contactId . time(),
                    'memo'          => $package['description'],
                    'attachment'    => ["https://kledo-live-user.s3.ap-southeast-1.amazonaws.com/rizal.api.kledo.com/finance/invoice/attachment/temp/211123/113415/Approve%20_%20Non%20Approve.png"],
                    'items'         => [[
                        'finance_account_id' => $product['id'],
                        'tax_id'            => null,
                        'desc'              => $package['description'],
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

                $subscription = [
                    'subscriptionId' => $subsId,
                    'packageId'     => $package['packageId'],
                    'packagePriceId' => $packagePriceId,
                    'userId'        => $data->data->userId,
                    'period'        => $packagePrice['period'],
                    'assetMax'      => $package['assetMax'],
                    'parameterMax'  => $package['parameterMax'],
                    'tagMax'        => $package['tagMax'],
                    'trxDailyMax'   => $package['trxDailyMax'],
                    'userMax'       => $package['userMax'],
                    'activeFrom'    => $activeFrom,
                    'expiredDate'   => $expiredDate
                ];
                $subscriptionModel->insert($subscription);
                $dueDateTrx = new DateTime();
                $transaction = [
                    'transactionId' => null,
                    'subscriptionId' => $subsId,
                    'packageId'     => $package['packageId'],
                    'packagePriceId' => $packagePriceId,
                    'invoiceId'     => $dataInvoice->id,
                    'refNumber'     => $dataInvoice->ref_number,
                    'userId'        => $data->data->userId,
                    'period'        => $packagePrice['period'],
                    'description'   => $package['description'],
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

                return $this->response->setJSON([
                    'status' => 200,
                    'message' => "Success Create App",
                    'data' => $data
                ], 200);
            }
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => $e
            ], 500);
        }
    }

    public function verifyMail($token = "")
    {
        $dataView = array(
            'title' => 'Verift Email | Logsheet Digital',
            'subtitle' => 'Logsheet Digital'
        );

        try {
            $data = JWT::decode($token, getenv('JWT_SECRET_KEY_MAIL_VERIFY'), ['HS256']);

            if ($data->iss !== getenv("DOMAIN_NAME")) {
                $dataView["titleVerified"] = "Invalid Token";
                $dataView["messageVerified"] = "An error occured trying to verify your account. the access token for Logsheet Digital email verification is invalid and may need to <a href='' class='text-info'>re-authorized</a>.";
            }

            try {
                $hr2Model = new HttpRequest2Model();

                helper('JWTAuth');
                $jwtData = getJWTData($token, getenv('JWT_SECRET_KEY_MAIL_VERIFY'));

                $urlReq = env("usmanURL") . "api/users/validate_email";
                $dataRes = $hr2Model->doRequest($urlReq, "post", ["userId" => $jwtData->userId]);

                $resData = $dataRes['data'];
                if ($dataRes['error']) {
                    $dataView["titleVerified"] = "Error Occured";
                    $dataView["messageVerified"] = $resData->message ?? $dataRes['message'];
                } else {
                    $dataView["titleVerified"] = "Verified";
                    $dataView["messageVerified"] = "You have successfully verified your account, now sign in with your new account.";
                }
            } catch (Exception $e) {
                $dataView["titleVerified"] = "Error Occured";
                $dataView["messageVerified"] = $e->getMessage();
            }
        } catch (Exception $e) {
            $dataView["titleVerified"] = "Invalid Token";
            $dataView["messageVerified"] = "An error occured trying to verify your account. the access token for Logsheet Digital email verification is invalid and may need to <a href='' class='text-info'>re-authorized</a>.";
        }

        return $this->template->render('Auth/verifyMail', $dataView);
    }
}
