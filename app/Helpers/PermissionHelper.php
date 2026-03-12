<?php

namespace App\Helpers;

class PermissionHelper
{
    public static function has($permissions, $module, $action = null)
    {
        if (empty($permissions)) return true;

        if (!isset($permissions[$module])) return false;

        if ($action) {
            return isset($permissions[$module][$action]) && $permissions[$module][$action] == 1;
        }

        return true;
    }
}
