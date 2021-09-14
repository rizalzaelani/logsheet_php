<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusAssetModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_assetStatus';
    protected $primaryKey           = 'assetStatusId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['assetStatusId', 'assetStatusName'];
    protected $createdField         = 'created_at';
}
