<?php

namespace App\Models;

use CodeIgniter\Model;

class ParameterModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_parameter';
    protected $primaryKey           = 'parameterId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['parameterId', 'assetId', 'sortId', 'parameterName', 'photo1', 'photo2', 'photo3', 'description', 'uom', 'min', 'max', 'normal', 'abnormal', 'option', 'inputType', 'showOn'];
    protected $createdField         = 'createdAt';
    protected $updatedField         = 'updatedAt';
    protected $deletedField         = 'deletedAt';
    // protected $useSoftDeletes        = true;

    public function getById($parameterId)
    {
        return $this->builder('vw_parameter')->where('parameterId', $parameterId)->get()->getRowArray();
    }

    public function getAll(array $where = null)
    {
        $query = $this->builder("vw_parameter");
        if ($where != null) {
            $query = $query->where($where);
        }

        return $query->get()->getResultArray();
    }
}
