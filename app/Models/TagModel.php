<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'tblm_tag';
	protected $primaryKey           = 'tagId';
	protected $returnType           = 'array';
	protected $allowedFields        = ['tagId', 'userId', 'tagName', 'creatdeAt'];
	protected $createdField         = 'created_at';
}
