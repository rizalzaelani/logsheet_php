<?php

namespace App\Models;

use CodeIgniter\Model;

class FindingModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_finding';
    protected $primaryKey           = 'findingId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['findingId','trxId','condition','openedBy','openedAt','closedBy','closedAt','findingPriority'];

    public function getById($findingId){
        return $this->builder("vw_finding")->where($this->primaryKey, $findingId)->get()->getRowArray();
    }
}
