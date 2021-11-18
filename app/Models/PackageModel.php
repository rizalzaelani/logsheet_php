<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class PackageModel extends Model
{
    protected $DBGroup = 'wizard';
    protected $table = 'tblm_package';
    protected $primaryKey = 'packageId';
    protected $allowedFields = ['packageId', 'name', 'description', 'assetMax', 'parameterMax', 'tagMax', 'trxDailyMax', 'userMax'];

    public function getAll()
    {
        return $this->orderBy('assetMax', 'asc')->findAll();
    }

    public function getByName(array $where = null)
    {
        $query = $this->builder("vw_package");
        if ($where != null) {
            $query = $query->where($where)->orderBy('userMax', 'asc');
        }

        return $query->get()->getResultArray();
    }
    public function getAllPackage()
    {
        $query = $this->builder("vw_package");
        return $query->get()->getResultArray();
    }
}
