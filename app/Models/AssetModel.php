<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tblm_asset';
    protected $primaryKey       = 'assetId';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['assetId', 'userId', 'assetStatusId', '', 'assetName', 'assetNumber', 'description', 'frequencyType', 'frequency', 'latitude', 'longitude', 'createdAt', 'updatedAt', 'deletedAt'];
}
