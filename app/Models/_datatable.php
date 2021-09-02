<?php

namespace App\Models;

use CodeIgniter\Model;

class _datatable extends Model
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

        // $filCompany = $_POST['columns'][1]['search']['value'] ?? "";
        // if ($filCompany != "") {
        //     $this->builder->where('company', $filCompany);
        // }

        // $filArea = explode(",", $_POST['columns'][2]['search']['value'] ?? "");
        // $filArea = array_values(array_filter($filArea, function ($val) {
        //     return $val != "";
        // }));
        // if (!empty($filArea)) {
        //     $this->builder->whereIn('area', $filArea);
        // }

        // $filUnit = explode(",", $_POST['columns'][3]['search']['value'] ?? "");
        // $filUnit = array_values(array_filter($filUnit, function ($val) {
        //     return $val != "";
        // }));
        // if (!empty($filUnit)) {
        //     $this->builder->whereIn('unit', $filUnit);
        // }

        if (isset($_POST['order'])) {
            $this->builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $this->order = $this->order;
            $this->builder->orderBy(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function datatable($data = '')
    {
        $this->_datatable_query();
        if ($_POST['length'] != -1) {
            $this->builder->limit($_POST['length'], $_POST['start']);
        }
        if ($data != '') {
            $this->builder->where($data);
        }
        return $this->builder->limit($_POST['length'])->get()->getResult();
    }

    public function count_filtered($data = '')
    {
        $this->_datatable_query();
        if ($data != '') {
            $this->builder->where($data);
        }
        return $this->builder->countAllResults();
    }

    public function count_all($data = '')
    {
        $dt = $this->db->table($this->table);
        if ($data != '') {
            $dt->where($data);
        }
        return $dt->countAllResults();
    }

    // public function getListCompany()
    // {
    //     $this->builder->select(('company'));
    //     $this->builder->orderBy('company', 'asc');
    //     $query = $this->builder->get();
    //     $result = $query->getResult();
    //     $company = array();
    //     foreach ($result as $row) {
    //         $company[] = $row->company;
    //     }
    //     return $company;
    // }
}
