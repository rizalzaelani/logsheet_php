<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tblm_asset';
    protected $primaryKey       = 'assetId';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['assetId', 'userId', 'assetStatusId', 'assetName', 'assetNumber', 'photo', 'description', 'schManual', 'schType', 'schFrequency', 'schWeeks', 'schWeekDays', 'schDays', 'latitude', 'longitude', 'createdAt', 'updatedAt', 'deletedAt'];
    protected $createdField     = 'createdAt';
    protected $updatedField     = 'updatedAt';
    protected $deletedField     = 'deletedAt';
    protected $useSoftDeletes   = true;

    public function getById($assetId)
    {
        return $this->builder('vw_asset')->where('assetId', $assetId)->get()->getRowArray();
    }

    public function getAll(array $where = null){
        $query = $this->builder("vw_asset");
        if($where != null){
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }

    public function getRecordTrendParam($parameterId, $dateFrom, $dateTo)
    {
        $query = $this->db->query("call sp_trendParameter('$parameterId','$dateFrom','$dateTo')");
        return $query->getResultArray();
    }
}
