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
}
