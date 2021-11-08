<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetTaggingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tblm_assetTagging';
    protected $primaryKey       = 'assetTaggingId';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['assetTaggingId', 'assetId', 'assetTaggingValue', 'assetTaggingtype', 'description'];

    public function getAll(array $where = null){
        $query = $this->builder("vw_assetTagging");
        if($where != null){
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }

    public function deleteById($assetId)
    {
        return $this->builder('tblm_assetTagging')->where('assetId', $assetId)->delete();
    }
}
