<?php

namespace Controller;
class ErrorController extends \Controller\BaseController
{
    public function page404()
    {
        $this->render('page404', []);
    }

    public function page403()
    {
        $this->render('page403', []);
    }

    public function page503($e)
    {
        $context = 
        [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
        $this->render('page503', $context);
    }
}

?>