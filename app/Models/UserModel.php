<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $DBGroup = 'user';
    protected $table = 'tblm_user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['appId', 'name', 'email', 'username', 'password'];

    public function getsLatest()
    {
        $res = $this->db->table('user as u')
            ->join('app as a', 'a.id=u.appId')
            ->select('u.name, u.email, u.username, u.createdAt')
            ->where('a.code', getAppCode())
            ->orderBy('createdAt', 'DESC')
            ->get(10);

        return $res->getResultArray();
    }

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById(array $userId = [])
    {
        return $this->builder()
            ->whereIn('userId', $userId)
            ->get()->getResultArray();
    }

    public function getCountByUsername(string $username)
    {
        $builder = $this->builder();
        $count = $builder->selectCount('id', 'count')->getWhere([
            'username' => $username
        ]);

        return $count->getRow();
    }

    public function getsByUsername(string $username)
    {
        $builder = $this->builder();
        $res = $builder->getWhere([
            'username' => $username
        ]);

        return $res->getResultArray();
    }

    public function getUserByApp(string $appCode){
        return $this->db->query("CALL sp_getUserByApp('$appCode')")->getResult();
    }

    public function validateUsername(string $username)
    {
        $user = $this
            ->asArray()
            ->where(['username' => $username])
            ->first();

        if (!$user) 
            throw new Exception('User does not exist for specified username');

        return $user;
    }
}
