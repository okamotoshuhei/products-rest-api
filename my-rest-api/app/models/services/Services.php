<?php
namespace Modules\Models\Services;

abstract class Services
{
    public static function getService($name)
    {
        $className = "\\Modules\\Models\\Services\\Service\\{$name}";
        
        return new $className();
    }
}
