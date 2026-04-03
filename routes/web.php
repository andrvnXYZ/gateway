<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return "Gateway is Running!";
});


$router->post('login', 'AuthController@login');


$router->group(['middleware' => 'auth'], function () use ($router) {
    
    // Ang tanang requests padung sa /users i-handle sa GatewayController
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users/{any:.*}', 'GatewayController@handleRequest');

    // Ang tanang requests padung sa /rentals i-handle sa GatewayController
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals/{any:.*}', 'GatewayController@handleRequest');

});