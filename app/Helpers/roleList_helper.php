<?php

if (!function_exists('checkRoleList')) {
    function checkRoleList($roleCheck)
    {
        $roleCheck = explode(",", $roleCheck);
        $roleList = explode(",", ($_SESSION["roleList"] ?? ""));
        if(count(array_intersect($roleList, $roleCheck)) > 0){
            return true;
        } else {
            return false;
        }
    }
}

?>