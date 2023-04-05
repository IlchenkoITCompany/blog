<?php

namespace Controller;
class LoginController extends \Controller\BaseController
{
    public function login(array $params)
    {
        $loginForm = [];
        if(!is_null($params['formData'])) {
            $loginForm = \Form\Login::getNormalizedData($params['formData']);
            if(!isset($loginForm['__errors'])) {
                $loginForm = \Form\Login::getPreparedData($loginForm);
                $userId = \Form\Login::verifyUser($loginForm);
                if($userId) {
                    session_start();
                    $_SESSION['currentUser'] = $userId;
                    \Helpers\redirect('/blog/user/'.$userId);
                }
            }
        } else {
            $loginForm = \Form\Login::getInitialData();
        }
        $context = 
        [
            'form' => $loginForm,
            'siteTitle' => 'Вход'
        ];
        $this->render('login', $context);
    }

    public function logout(array $params)
    {
        if(!is_null($params['formData'])) {
            unset($_SESSION['currentUser']);
            session_destroy();
            \Helpers\redirect('/blog/');
        } else {
            $context = ['siteTitle' => 'Выход'];
            $this->render('logout', $context);
        }
    }

    public function register(array $params)
    {
        $regFrom = [];
        if(!is_null($params['formData'])) {
            $regForm = \Form\Register::getNormalizedData($params['formData']);
            if(!isset($regForm['__errors'])) {
                $regForm = \Form\Register::getPreparedData($regForm);
                $users = new \Model\User;
                $newUserId = $users->insert($regForm);
                $users2 = new \Model\User;
                $currentUser = $users2->get($newUserId, 'id');
                session_start();
                $_SESSION['__currentUser'] = $currentUser;
                \Helpers\redirect('/blog/user/'.$currentUser['id']);
            }
        } else {
            $regForm = \Form\Register::getInitialData();
        }
        $context = 
        [
            'form' => $regForm,
            'siteTitle' => 'Регистрация',
        ];
    }
}

?>