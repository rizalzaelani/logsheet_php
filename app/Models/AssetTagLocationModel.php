<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetTagLocationModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblmb_assetTagLocation';
    protected $primaryKey           = 'assetTagLocationId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['assetTagLocationId', 'assetId', 'tagLocationId'];
    protected $createdField         = 'created_at';

    public function deleteById($assetId)
    {
        return $this->builder('tblmb_assetTagLocation')->where('assetId', $assetId)->delete();
    }

    public function deleteTagLocationId($tagLocationId)
    {
        return $this->builder('tblmb_assetTagLocation')->where('tagLocationId', $tagLocationId)->delete();
    }
    public function getAll(array $where = null)
    {
        $query = $this->builder();
        if ($where != null) {
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }
}
