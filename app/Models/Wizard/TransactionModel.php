<?php

namespace App\Models\Wizard;

use CodeIgniter\Model;
use Exception;

class TransactionModel extends Model
{
    protected $DBGroup = 'wizard';
    protected $table = 'tblt_transaction';
    protected $primaryKey = 'transactionId';
    protected $allowedFields = ['transactionId', 'subscriptionId', 'userId', 'period', 'description', 'paymentTotal', 'paymentMethod', 'attachment', 'issueDate', 'paidDate', 'approvedDate', 'cancelDate', 'activeFrom', 'activeTo'];

    public function getAll()
    {
        return $this->findAll();
    }
    public function getById($where)
    {
        return $this->where('transactionId', $where)->findAll();
    }
}
