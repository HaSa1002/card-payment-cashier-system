<?php

$router = $di->getRouter();

// Define your routes here
$router->add(
    '/logout/',
    [
        'controller' => 'index',
        'action'     => 'logout',
    ]
);

$router->add(
    '/select/',
    [
        'controller' => 'index',
        'action'     => 'select',
    ]
);


$router->handle();
