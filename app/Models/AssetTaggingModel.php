<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetTaggingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tblm_assetTagging';
    protected $primaryKey       = 'assetTaggingId';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['assetTaggingId', 'assetId', 'assetTaggingValue', '', 'assetTaggingtype', 'description'];

    public function deleteById($assetId)
    {
        return $this->builder('tblm_assetTagging')->where('assetId', $assetId)->delete();
    }
}
