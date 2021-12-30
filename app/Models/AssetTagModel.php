<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetTagModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblmb_assetTag';
    protected $primaryKey           = 'assetTagId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['assetTagId', 'assetId', 'tagId'];
    protected $createdField         = 'created_at';

    public function deleteById($assetId)
    {
        return $this->builder('tblmb_assetTag')->where('assetId', $assetId)->delete();
    }

    public function deleteByIdTag($tagId)
    {
        return $this->builder('tblmb_assetTag')->where('tagId', $tagId)->delete();
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
