<?php

$rules = 
[
    '/^$/' => 
    [
        'controller' => 'Image',
        'action' => 'list',
        'params' => NULL,
    ],
    
    '/^(\d+)$/' =>
    [
        'controller' => 'Image',
        'action' => 'item',
        'params' => ['pictureId'],
        'requestMethod' => 'POST',
    ],

    '/^users\/(\d+)$/' =>
    [
        'controller' => 'Image',
        'action' => 'by_user',
        'params' => ['userId'],
    ],

    '/^cats\/(\w+)$/' => 
    [
        'controller' => 'Image',
        'action' => 'by_cat',
        'params' => ['categoryId'],
    ],

    '/^users\/(\d+)\/pictures\/(\d+)\/edit$/' =>
    [
        'controller' => 'Image',
        'action' => 'edit',
        'params' => ['userId', 'pictureId'],
        'requestMethods' => 'POST',
    ]
]

?>