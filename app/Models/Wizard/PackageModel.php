<?php

namespace App\Models\Wizard;

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

    public function getById($packageId)
    {
        return $this->builder('tblm_package')->where($this->primaryKey, $packageId)->get()->getResultArray();
    }

    public function getByName(array $where = null)
    {
        $query = $this->builder("vw_package");
        if ($where != null) {
            $query = $query->where($where)->orderBy('userMax', 'asc');
        }

        return $query->get()->getResultArray();
    }
    public function getAllPackage(array $where = null)
    {
        $query = $this->builder("vw_package");
        if ($where != null) {
            $query = $query->where($where);
        }
        return $query->get()->getResultArray();
    }
}
