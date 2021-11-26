<?php

namespace App\Models\Wizard;

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

    public function getByIdPrice($packagePriceId)
    {
        return $this->where('packagePriceId', $packagePriceId)->get()->getRowArray();
    }
}
