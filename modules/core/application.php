<?php
namespace Core;
final class Application 
{
    public function run($rules, $requestPath)
    {
        $router = new \Core\Router($rules, $requestPath);
        $router->createController();
    }
}

?>