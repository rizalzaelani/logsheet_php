<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_notification';
    protected $primaryKey           = 'notificationId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['notificationId', 'userId', 'friendlyName', 'type', 'trigger', 'value', 'status', 'deletedAt'];
    protected $createdField         = 'createdAt';
    protected $deletedField         = 'deletedAt';
    protected $useSoftDeletes       = true;

    public function getById($id)
    {
        return $this->builder()->where($this->primaryKey, $id)->get()->getRowArray();
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