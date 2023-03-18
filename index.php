<?php
$basePath = __DIR__.'/';
require_once $basePath.'modules/core/autoloader.php';
require_once $basePath.'modules/settings.php';
require_once $basePath.'modules/routes.php';

$requestPath = '';
$result = [];

if ($_GET) 
{
    $requestPath = $_GET['route'];
}

if ($requestPath && $requestPath[-1] == "/") 
{
    $requestPath = substr($requestPath, 0, strlen($requestPath) - 1);
}

require_once $basePath.'modules/exceptionshandler.php';

$app = new \Core\Application;
$app->run($rules, $requestPath);

?>