<?php

$rules = 
[
    '/^$/' => 
    [
        'controller' => 'Record',
        'action' => 'list',
        'params' => NULL,
    ],
    
    '/^(\d+)$/' =>
    [
        'controller' => 'Record',
        'action' => 'item',
        'params' => ['recordId'],
        'requestMethod' => 'POST',
    ],

    '/^login$/' => 
    [
        'controller' => 'Login',
        'action' => 'login',
        'params' => NULL,
        'requestMethod' => 'POST',
    ],

    '/^logout$/' => 
    [
        'controller' => 'Login',
        'action' => 'logout',
        'params' => NULL,
        'requestMethod' => 'POST',
    ],

    '/^user\/(\d+)$/' =>
    [
        'controller' => 'Record',
        'action' => 'by_user',
        'params' => ['userId'],
    ],

    '/^user\/(\d+)\/picture\/add/' =>
    [
        'controller' => 'Record',
        'action' => 'add',
        'params' => ['userId'],
        'requestMethod' => 'POST',
    ],

    '/^theme\/(\w+)$/' => 
    [
        'controller' => 'Record',
        'action' => 'by_cat',
        'params' => ['themeSlug'],
    ]
]

?>