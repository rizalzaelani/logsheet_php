<?php

namespace App\Models;

use CodeIgniter\Model;

class TagLocationModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_tagLocation';
    protected $primaryKey           = 'tagLocationId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['tagLocationId', 'tagLocationName', 'latitude', 'longitude', 'description'];
    protected $createdField         = 'createdAt';

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
