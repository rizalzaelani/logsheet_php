<?php

namespace App\Models\Wizard;

use CodeIgniter\Model;
use Exception;

class SubscriptionModel extends Model
{
    protected $DBGroup = 'wizard';
    protected $table = 'tblm_subscription';
    protected $primaryKey = 'subscriptionId';
    protected $allowedFields = ['subscriptionId', 'packageId', 'packagePriceId', 'userId', 'period', 'assetMax', 'parameterMax', 'tagMax', 'trxDailyMax', 'userMax', 'activeFrom', 'expiredDate', 'createdAt', 'deletedAt'];

    public function getAll()
    {
        return $this->findAll();
    }
}
