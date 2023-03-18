<?php

namespace Core;
class Autoloader
{
    static function loadClass($className)
    {
        global $basePath;
        $normalClassName = str_replace('\\', '/', $className);
        $filePath = $basePath.'modules/'.$normalClassName.'.php';
        if(file_exists($filePath)) {
            require_once $filePath;
        } else {
            echo 'Такого файла не существует: ' . $filePath;
        }
    }
}

spl_autoload_register(['\Core\Autoloader', 'loadClass']);


?>