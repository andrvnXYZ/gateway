<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return "Gateway is Running!";
});

// KINI DAPAT NAA SA GAWAS SA MIDDLEWARE
$router->post('login', 'AuthController@login');
$router->post('register', 'GatewayController@handleRequest'); // I-add ni!
// Sa Gateway routes/web.php
$router->post('login', 'GatewayController@handleRequest');

$router->group(['middleware' => 'auth'], function () use ($router) {
    
    // Ang tanang requests padung sa /users
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users/{any:.*}', 'GatewayController@handleRequest');

    // Ang tanang requests padung sa /rentals
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals/{any:.*}', 'GatewayController@handleRequest');
});