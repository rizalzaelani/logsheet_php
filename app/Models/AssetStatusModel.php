<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetStatusModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_assetStatus';
    protected $primaryKey           = 'assetStatusId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['assetStatusId', 'assetStatusName'];
    protected $createdField         = 'created_at';
    
    public function getAll(array $where = null){
        $query = $this->builder();
        if($where != null){
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }
}
