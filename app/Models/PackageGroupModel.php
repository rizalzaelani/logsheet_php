<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class PackageGroupModel extends Model
{
    protected $DBGroup = 'wizard';
    protected $table = 'tblm_packageGroup';
    protected $primaryKey = 'packageGroupId';
    protected $allowedFields = ['packageGroupId', 'packageGroupName', 'cretedAt'];

    public function getAll()
    {
        return $this->orderBy('createdAt', 'asc')->findAll();
    }
}
