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
	protected $createdField         = 'createdAt';
}
