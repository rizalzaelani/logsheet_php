<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetStatusModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_assetStatus';
    protected $primaryKey           = 'assetStatusId';
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = ['assetStatusId', 'userId', 'assetStatusName', 'createdAt', 'updatedAt', 'deletedAt'];
    protected $createdField         = 'createdAt';
    protected $updatedField         = 'updatedAt';
    protected $deletedField         = 'deletedAt';
    
    public function getAll(array $where = null){
        $query = $this->builder();
        if($where != null){
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }

    public function deleteById($assetStatusId)
    {
        return $this->builder()->where($this->primaryKey, $assetStatusId)->delete();
    }
}
