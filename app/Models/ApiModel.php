<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'tblm_adminequip';
	protected $primaryKey           = 'adminequip_id';
	protected $useAutoIncrement     = true;
	protected $allowedFields        = ["company", "area", "unit", "equipment"];
}
