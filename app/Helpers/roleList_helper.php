<?php

if (!function_exists('checkRoleList')) {
    function checkRoleList($roleCheck)
    {
        $sess = \Config\Services::session();

        $roleCheck = explode(",", $roleCheck);
        $roleList = explode(",", ($sess->get('roles') ?? ""));
        if (count(array_intersect($roleList, $roleCheck)) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
