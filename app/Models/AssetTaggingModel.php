<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetTaggingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tblm_assetTagging';
    protected $primaryKey       = 'assetTaggingId';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['assetTaggingId', 'assetId', 'assetTaggingValue', '', 'assetTaggingType', 'description'];
}
