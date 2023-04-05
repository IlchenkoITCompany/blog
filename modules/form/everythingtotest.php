<?php

namespace Form;
class EverythingToTest extends \Form\BaseForm
{
    protected const FIELDS = 
    [
        'name' => ['fieldType' => 'input', 'type' => 'string', 'inputType' => 'text', 'labelName' => 'Введите имя пользователя', 'placeholder' => 'Тут должно имя'],

        'email' => ['fieldType' => 'input', 'type' => 'email', 'inputType' => 'email', 'labelName' => 'Введите свою электронную почту'],
        
        'contents' => ['fieldType' => 'textarea', 'type' => 'string', 'labelName' => 'Введите текста статьи'],

        'user' => ['fieldType' => 'select', 'type' => 'string', 'labelName' => 'Выберите пользователя из списка'],

        'emailme' => ['fieldType' => 'checkbox', 'type' => 'boolean', 'labelName' => 'Согласны ли вы получать нашу рассылку на почту', 'initial' => TRUE]
    ];

    protected const FILES_FIELDS = 
    [
        'images' => ['labelName' => 'Прикрепите файл', 'optional' => FALSE, 'maxFilesCount' => 3, 'optional' => FALSE, 'extensions' => \Settings\IMAGES_EXTENSIONS]
    ];
}

?>