<?php

namespace App\Models;

use CodeIgniter\Model;

class FindingLogModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_findingLog';
    protected $primaryKey           = 'findingLogId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['findingLogId', 'findingId','notes','attachment','createdBy'];
    protected $createdField         = 'createdAt';

    public function getAll(array $where = null){
        $query = $this->builder();
        if($where != null){
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }
}
