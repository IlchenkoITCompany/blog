<?php

class ExceptionsHandler
{
    public static function exceptionHandler($e)
    {
        $controller = new \Controller\ErrorController;

        if($e instanceof \Exception\Page404Exception) {
            $controller->page404();
        } else if ($e instanceof \Exception\Page403Exception) {
            $controller->page403();
        } else {
            $controller->page503($e);
        }
    }
}

set_exception_handler(['ExceptionsHandler', 'exceptionHandler']);

?>