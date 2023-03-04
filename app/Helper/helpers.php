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

if (!function_exists('downloadFile')) {
    /**
     * @param string $url  网址
     * @param string $path 保存地址
     *
     * @return void
     **/
    function downloadFile(string $url, string $path): void
    {
        set_time_limit(0);
        $fp = fopen ($path, 'w+');
        $ch = curl_init(str_replace(" ","%20",$url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

}
