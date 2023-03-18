<?php

namespace Helpers
{
    function render(string $template, array $context)
    {
        global $basePath;
        extract($context);
        require_once $basePath.'modules/templates/'.$template.'.php';
    }

    function connectToDb() 
    {
        $connection_string = 'mysql:host='.\Settings\DB_HOST.';dbname='.\Settings\DB_NAME.';charset=utf8';
        return new \PDO($connection_string, \Settings\DB_USERNAME, \Settings\DB_PASSWORD);
    }
}

?>