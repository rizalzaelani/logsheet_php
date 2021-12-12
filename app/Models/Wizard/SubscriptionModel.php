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

    public function getById($id)
    {
        return $this->builder()->where($this->primaryKey, $id)->get()->getRowArray();
    }

    public function getAll(array $where = null)
    {
        $query = $this->builder();
        if ($where != null) {
            $query = $query->where($where);
        }
        return $query->get()->getResultArray();
    }

    public function getByUser($userId){
        return $this->builder()->where('userId', $userId)->get()->getResultArray();
    }

    public function getAllData(array $where = null)
    {
        $query = $this->builder("vw_subscription");
        if ($where != null) {
            $query = $query->where($where)->orderBy('invoiceId', 'desc');
        }
        return $query->get()->getResultArray();

    }
}
