<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\Influx\LogModel;
use App\Models\ParameterModel;
use App\Models\ScheduleTrxModel;
use App\Models\TagModel;
use App\Models\TagLocationModel;
use App\Models\Wizard\KledoModel;
use App\Models\Wizard\PackageModel;
use App\Models\Wizard\PackagePriceModel;
use App\Models\Wizard\SubscriptionModel;
use App\Models\Wizard\TransactionModel;
use DateTime;
use Exception;

class Dashboard extends BaseController
{
	public function index()
	{
		if (!checkRoleList("DASHBOARD.VIEW")) {
			return View('errors/customError', ['errorCode' => 403, 'errorMessage' => "Sorry, You don't have access to this page"]);
		}

		$assetModel			= new AssetModel();
		$tagModel			= new TagModel();
		$locationModel		= new TagLocationModel();
		$scheduleTrxModel	= new ScheduleTrxModel();
		$subscriptionModel	= new SubscriptionModel();
		$parameterModel		= new ParameterModel();

		$adminId = $this->session->get('adminId');

		$approvedAtNull = $scheduleTrxModel->getAll(['userId' => $adminId, 'approvedAt' => null]);
		$approvedAtNotNull = $scheduleTrxModel->getAll(['userId' => $adminId, 'approvedAt !=' => null]);
		$normal = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Normal']);
		$finding = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Finding']);
		$open = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Open']);
		$closed = $scheduleTrxModel->getAll(['userId' => $adminId, 'condition' => 'Closed']);
		$dataAsset = $assetModel->getAll(['userId' => $adminId, 'deletedAt' => null]);
		$dataTag = $tagModel->getAll(['userId' => $adminId]);
		$dataLocation = $locationModel->getAll(['userId' => $adminId]);
		$dataParameter = $parameterModel->getAll(['userId' => $adminId]);

		$dataSubscription = $subscriptionModel->getAll(['userId' => $adminId]);
		$subscription = "";
		if (!empty($dataSubscription)) {
			$subscription = $dataSubscription[0];
		}else{
			$this->createSubscription();
		}
		
		$dataSubscription = $subscriptionModel->getAll(['userId' => $adminId]);
		if (!empty($dataSubscription)) {
			$subscription = $dataSubscription[0];
		}
		
		$data = array(
			'title' => "Dashboard",
			'subtitle' => 'Dashboard Equipment Record'
		);
		$data["breadcrumbs"] = [
			[
				"title"	=> "Home",
				"link"	=> "Dashboard"
			],
		];
		$data['totalAsset']		= count($dataAsset);
		$data['totalTag']		= count($dataTag);
		$data['totalLocation']	= count($dataLocation);
		$data['approveNull']	= count($approvedAtNull);
		$data['approveNotNull'] = count($approvedAtNotNull);
		$data['normal']			= count($normal);
		$data['finding']		= count($finding);
		$data['open']			= count($open);
		$data['closed']			= count($closed);

		$data['availableAsset']		= $subscription['assetMax'] - count($dataAsset);
		$data['availableParameter'] = $subscription['parameterMax'] - count($dataParameter);
		$data['availableTag']		= $subscription['tagMax'] - count($dataTag);
		$data['availableTagLocation']= $subscription['tagMax'] - count($dataLocation);

		return $this->template->render('Dashboard/index', $data);
	}

	public function getTagTagLoc()
	{
		try {
			$tagModel = new TagModel();
			$tagLocModel = new TagLocationModel();

			$tagData = $tagModel->getAll(["userId" => $this->session->get("adminId")]);
			$tagLocData = $tagLocModel->getAll(["userId" => $this->session->get("adminId")]);

			return $this->response->setJSON(array(
				'status' => 200,
				'message' => 'Success Get Data',
				'data' => [
					'tagData' => $tagData,
					'tagLocationData' => $tagLocData
				]
			), 200);
		} catch (Exception $e) {
			return $this->response->setJSON(array(
				'status' => 500,
				'message' => $e->getMessage(),
				'data' => []
			), 500);
		}
	}

	public function createSubscription()
	{
		$packageModel       = new PackageModel();
		$packagePriceModel  = new PackagePriceModel();
		$kledoModel         = new KledoModel();
		$subscriptionModel  = new SubscriptionModel();
		$transactionModel   = new TransactionModel();

		$bodyGetContact = [
			'search' => $this->session->get('name')
		];
		$cek = "";
		$cekContact = $kledoModel->getContact(http_build_query($bodyGetContact));
		if ($cekContact['error'] == false) {
			$res = $cekContact['data'];
			$resData = json_decode($res);
			$contact = "";
			if (count((array) $resData->data->data)) {
				$contact = $resData->data->data[0];

				
			}else{
				// add contact to kledo
				$address = $this->request->getVar('city') . ' ' . $this->request->getVar('postalCode') . ', ' . $this->request->getVar('country');
				$bodyAddContact = [
					'name'                  => $this->session->get('name'),
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
				if ($resContact->success) {
					$contact = $resContact->data;
				}
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
				'contact_id'    => $contact->id,
				'status_id'     => 1,
				"include_tax"   => 0,
				'term_id'       => 1,
				'ref_number'    => "INVLD" . $contact->id . time(),
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
				'userId'        => $this->session->get('adminId'),
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
				'userId'        => $this->session->get('adminId'),
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
		}
	}
}
