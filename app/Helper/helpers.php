<?php
if (!function_exists('adminUrlPrefix')) {
    function adminUrlPrefix(): string
    {
        return '/' . config('admin.prefix');
    }
}

//输出model的命名空间
if (!function_exists('getClassName')) {
    function getClassName(object $object): string
    {
        return addslashes(get_class($object));
    }
}
