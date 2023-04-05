<?php

namespace Form;
class Login extends \Form\BaseForm
{
    protected const FIELDS = 
    [
        'name' => ['type' => 'string'],
        'password' => ['type' => 'string'],
    ];

    public static function verifyUser(&$data) 
    {
        $errors = [];
        $users = new \Model\User;
        $user = $users->get($data['name'], 'name', 'id, password, active');
        if(!$user) {
            $errors['name'] = 'Неверное имя пользователя';
        } else {
            if(!$user['active']) {
                $errors['name'] = 'Этот пользователь неактивен';
            } else {
                if (!password_verify($data['password'], $user['password'])) {
                    $errors['name'] = 'Неверный пароль';
                } else {
                    return $user['id'];
                }
            }
        }
        $data['__errors'] = $errors;
    }
}

?>