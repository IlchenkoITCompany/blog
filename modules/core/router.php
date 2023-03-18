<?php

namespace Core;

final class Router
{
    private $rules;
    private $requestPath;
    private $requestMethod;

    private $controllerName;
    private $actionName;

    public function __construct(array $rules, string $requestPath)
    {
        $this->rules = $rules;
        $this->requestPath = $requestPath;
    }

    /**
     * this method define controllerName and actionName by params from url patterns and request methods
     */
    private function findController() 
    {
        $actionParams = array();
        $formArray = array();
        foreach($this->rules as $url => $params) {
            if(preg_match($url, $this->requestPath, $result) === 1) {
                if (isset($params['requestMethod'])) {
                    $this->requestMethod = $params['requestMethod'];
                } else {
                    $this->requestMethod = 'GET';
                }

                if(isset($params['params'])) {
                    for ($i = 0; $i < count($params['params']); $i++) {
                        $actionParams[$params['params'][$i]] = $result[$i+1];
                    }
                } 

                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === $this->requestMethod && $this->requestMethod !== 'GET') {
                    $this->controllerName = $params['controller'].'Form';
                    $this->actionName = $params['action'];

                    return ['formData' => $_POST, 'params' => $actionParams];
                } else {
                    $this->controllerName = $params['controller'];
                    $this->actionName = $params['action'];
                    
                    return ['params' => $actionParams];
                }
            }
        }
    }

    public function createController() 
    {
        $pregMatchResult = $this->findController();
        $controllerName = $this->getControllerName();
        $actionName = $this->getActionName();
        if($controllerName !== '' && $actionName) {
            $controller = new $controllerName;
            $controller->$actionName($pregMatchResult);
        } else {
            throw new \Exception\Page404Exception;
        }
    }

    private function getControllerName()
    {
        if($this->controllerName !== '') {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === $this->requestMethod && $this->requestMethod !== 'GET') {
                return '\FormController\\'.$this->controllerName.'Controller';
            } else {
            return '\Controller\\'.$this->controllerName.'Controller';
            }
        } else {
            return '';
        }
    }

    private function getActionName()
    {
        return $this->actionName;
    }
}

?>