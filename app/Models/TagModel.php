<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_tag';
    protected $primaryKey           = 'tagId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['tagId', 'userId', 'tagName', 'description', 'creatdeAt'];
    protected $createdField         = 'created_at';

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
