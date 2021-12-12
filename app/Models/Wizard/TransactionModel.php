<?php

namespace App\Models\Wizard;

use CodeIgniter\Model;
use Exception;

class TransactionModel extends Model
{
    protected $DBGroup = 'wizard';
    protected $table = 'tblt_transaction';
    protected $primaryKey = 'transactionId';
    protected $allowedFields = ['transactionId', 'subscriptionId', 'packageId', 'packagePriceId', 'userId', 'invoiceId', 'refNumber', 'period', 'description', 'paymentTotal', 'paymentMethod', 'attachment', 'issueDate', 'dueDate', 'paidDate', 'approvedDate', 'cancelDate', 'activeFrom', 'activeTo'];

    public function getAll()
    {
        return $this->findAll();
    }
    public function getById($where)
    {
        return $this->where('transactionId', $where)->findAll();
    }
    public function getByUser(array $where = null)
    {
        $query = $this->builder('vw_transaction');
        if ($where != null) {
            $query = $query->where($where)->orderBy('createdAt', 'asc');
        }
        return $query->get()->getResultArray();
    }
}
