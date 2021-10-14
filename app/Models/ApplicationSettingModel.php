<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationSettingModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_applicationSetting';
    protected $primaryKey           = 'appSettingId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['appSettingId', 'userId', 'appName', 'appLogoLight', 'appLogoDark', 'appLogoIcon', 'createdAt', 'updatedAt'];
    protected $createdField         = 'createdAt';
    protected $updatedField         = 'updatedAt';

    public function getById($appSettingId)
    {
        return $this->builder()->where($this->primaryKey, $appSettingId)->get()->getRowArray();
    }

    public function deleteById($appSettingId)
    {
        return $this->builder()->where($this->primaryKey, $appSettingId)->delete();
    }
}
