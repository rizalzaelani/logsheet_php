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

    public function getAll(array $where = null){
        $query = $this->builder("vw_transaction")->orderBy("sortId", "asc");
        if($where != null){
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }
}
