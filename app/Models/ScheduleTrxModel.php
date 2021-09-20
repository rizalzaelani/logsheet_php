<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleTrxModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_scheduleTrx';
    protected $primaryKey           = 'scheduleTrxId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['scheduleTrxId','assetId','assetStatusId','frequencyType','frequency','scheduleFrom','scheduleTo','syncAt','scannedAt','scannedEnd','scannedBy','scannedWith','scannedNotes','scannedAccuration','approvedAt','approvedNotes','condition','createdAt'];
    protected $createdField         = 'createdAt';

    public function getById($id)
    {
        return $this->builder()->where($this->primaryKey, $id)->get()->getRowArray();
    }

    public function getAll(array $where = null){
        $query = $this->builder("vw_scheduleTrx");
        if($where != null){
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }
}
