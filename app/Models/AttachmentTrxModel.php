<?php

namespace App\Models;

use CodeIgniter\Model;

class AttachmentTrxModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_attachmentTrx';
    protected $primaryKey           = 'attachmentTrxId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['attachmentTrxId', 'scheduleTrxId', 'trxId', 'attachment', 'notes'];
    protected $createdField         = 'createdAt';
    protected $useSoftDeletes        = false;

}
