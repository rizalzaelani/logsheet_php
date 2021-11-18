<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class PackagePriceModel extends Model
{
    protected $DBGroup = 'wizard';
    protected $table = 'tblm_packagePrice';
    protected $primaryKey = 'packagePriceId';
    protected $allowedFields = ['packageId', 'packageId', 'period', 'price', 'createdAt', 'updatedAt'];

    public function getAll()
    {
        return $this->findAll();
    }
    public function getById($where)
    {
        return $this->where('packageId', $where)->findAll();
    }
}
