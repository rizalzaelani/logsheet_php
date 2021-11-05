<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleTrxModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_scheduleTrx';
    protected $primaryKey           = 'scheduleTrxId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['scheduleTrxId', 'assetId', 'assetStatusId', 'schManual', 'schType', 'schFrequency', 'schWeeks', 'schWeekDays', 'schDays', 'scheduleFrom', 'scheduleTo', 'adviceDate', 'syncAt', 'scannedAt', 'scannedEnd', 'scannedBy', 'scannedWith', 'scannedNotes', 'scannedAccuration', 'approvedAt', 'approvedBy', 'approvedNotes', 'condition'];
    protected $createdField         = 'createdAt';

    public function getById($id)
    {
        return $this->builder("vw_scheduleTrx")->where($this->primaryKey, $id)->get()->getRowArray();
    }

    public function getAll(array $where = null, $orderBy = "", $ascDesc = "asc")
    {
        $query = $this->builder("vw_scheduleTrx");
        if ($where != null) {
            $query = $query->where($where);
        }

        if($orderBy != ""){
            $query = $query->orderBy($orderBy, $ascDesc);
        }

        return $query->get()->getResultArray();
    }

    // public function getSchByAssetSchFrom(array $assetId, $asset){

    // }

    public function checkNormalAbnormal($scheduleTrxId)
    {
        return $this->db->query("call sp_normalAbnormalTrx('" . $scheduleTrxId . "')")->getResultArray();
    }
}
