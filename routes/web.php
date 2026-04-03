<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return "Gateway is Running!";
});

// KINI DAPAT NAA SA GAWAS SA MIDDLEWARE
// Gateway routes/web.php

$router->post('register', 'GatewayController@handleRequest');
$router->post('login', 'GatewayController@handleRequest');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users/{any:.*}', 'GatewayController@handleRequest');
    
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals/{any:.*}', 'GatewayController@handleRequest');
});