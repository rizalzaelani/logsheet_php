<?php

namespace App\Models;

use CodeIgniter\Model;

class AttachmentTrxModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tblt_attachmentTrx';
    protected $primaryKey           = 'attachmentTrxId';
    protected $returnType           = 'array';
    protected $allowedFields        = ['attachmentTrxId', 'scheduleTrxId', 'trxId', 'attachment', 'notes', 'createdAt'];
    protected $createdField         = 'createdAt';

    public function deleteAttachWhereIn(array $where){
        $this->builder()->whereIn("attachmentTrxId", $where)->delete();
    }
}
