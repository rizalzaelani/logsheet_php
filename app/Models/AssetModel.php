<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table      = 'tblm_asset';
    protected $primaryKey = 'assetId';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['assetName', 'assetNumber', 'description', 'frequencyType', 'frequency', 'createdAt', 'updatedAt', 'deletedAt'];

    public function import_data($dataequip)
    {
        $this->builder = $this->db->table('tblm_asset');
        $jumlah = count($dataequip);
        if ($jumlah > 0) {
            $this->builder->insert($dataequip);
        }
    }
}
