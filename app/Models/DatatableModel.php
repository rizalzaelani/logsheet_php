<?php

namespace App\Models;

use CodeIgniter\Model;

class DatatableModel extends Model
{
    protected $db;

    public function __construct($table, $column_order, $column_search, $order)
    {
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->table = $table;
        $this->column_order = $column_order;
        $this->column_search = $column_search;
        $this->order = $order;
        $this->builder = $this->db->table($this->table);
    }
    protected function _datatable_query()
    {
        $i = 0;
        foreach ($this->column_search as $search) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->builder->groupStart();
                    $this->builder->like($search, $_POST['search']['value']);
                } else {
                    $this->builder->orLike($search, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->builder->groupEnd();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $this->builder->orderBy(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function datatable($where = '')
    {
        $this->_datatable_query();
        if ($_POST['length'] != -1) {
            $this->builder->limit($_POST['length'], $_POST['start']);
        }
        if ($where != '') {
            $this->builder->where($where);
        }
        return $this->builder->limit($_POST['length'])->get()->getResult();
    }

    public function count_filtered($where = '')
    {
        $this->_datatable_query();
        if ($where != '') {
            $this->builder->where($where);
        }
        return $this->builder->countAllResults();
    }

    public function count_all($where = '')
    {
        $dt = $this->db->table($this->table);
        if ($where != '') {
            $dt->where($where);
        }
        return $dt->countAllResults();
    }
}
