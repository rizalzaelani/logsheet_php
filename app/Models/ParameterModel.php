<?php

namespace App\Models;

use CodeIgniter\Model;

class ParameterModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_parameter';
    protected $primaryKey           = 'parameterId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['parameterId', 'assetId', 'sortId', 'parameterName', 'photo', 'description', 'uom', 'min', 'max', 'normal', 'abnormal', 'option', 'inputType', 'showOn'];
    protected $createdField         = 'createdAt';
    protected $updatedField         = 'updatedAt';
    protected $deletedField         = 'deletedAt';
    protected $useSoftDeletes        = true;
}
