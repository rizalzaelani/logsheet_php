<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblm_tagLocation';
    protected $primaryKey           = 'tagLocationId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['tagLocationId', 'tagLocationName', 'latitude', 'longitude', 'description'];
    protected $createdField         = 'created_at';
}
