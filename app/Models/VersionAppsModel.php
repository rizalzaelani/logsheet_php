<?php

namespace App\Models;

use CodeIgniter\Model;

class VersionAppsModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_versionApp';
    protected $primaryKey           = 'versionAppId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['versionAppId', 'userId', 'name', 'version', 'description', 'fileApp', 'createdAt'];
    protected $createdField         = 'createdAt';

    public function getById($versionAppId)
    {
        return $this->builder()->where($this->primaryKey, $versionAppId)->get()->getRowArray();
    }

    public function deleteById($versionAppId)
    {
        return $this->builder()->where($this->primaryKey, $versionAppId)->delete();
    }
}
