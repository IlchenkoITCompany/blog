<?php

namespace Controller;
require_once 'modules/helpers.php';

class BaseController
{
    public $currentUser = NULL;

    function __construct() 
    {
        if(session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if(isset($_SESSION['currentUser'])) {
            $users = new \Model\User();
            $this->currentUser = $users->getOr404($_SESSION['currentUser']);
        } else {
            session_destroy();
        }
    }

    /**
     * add some elements to main array $context
     */
    protected function contextAppend(array &$context) 
    {
        $context['__currentUser'] = $this->currentUser;
    }

    /**
     * render template with function render from file modules/helpers.php
     * add some to array $context with function contentAppend()
     */
    public function render(string $template, array $context)
    {
        $this->contextAppend($context);
        \Helpers\render($template, $context);
    }
}

?>