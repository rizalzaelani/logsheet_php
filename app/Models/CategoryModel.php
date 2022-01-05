<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_categoryIndustry';
    protected $primaryKey           = 'categoryIndustryId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['categoryIndustryId', 'categoryName', 'description', 'image', 'createdAt', 'updatedAt', 'deletedAt'];
    protected $createdField         = 'createdAt';

    public function getById($id)
    {
        return $this->builder()->where($this->primaryKey, $id)->get()->getRowArray();
    }

    public function getAll(array $where = null)
    {
        $query = $this->builder('vw_template');
        if ($where != null) {
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }
}
