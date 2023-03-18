<?php

namespace Controller;
require_once 'modules/helpers.php';

class BaseController
{
    /**
     * add some elements to main array $context
     */
    protected function contextAppend(array &$context) 
    {

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