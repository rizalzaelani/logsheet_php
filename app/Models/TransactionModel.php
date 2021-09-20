<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_transaction';
    protected $primaryKey           = 'trxId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['trxId','scheduleTrxId','parameterId','value','condition','createdAt'];
    protected $createdField         = 'createdAt';
}
