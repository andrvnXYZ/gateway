<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return "Gateway is Running!";
});

// KINI NGA MGA ROUTES ANG DAPAT MA-IGO SA POSTMAN PARA SA LOGIN/REGISTER
$router->post('login', 'GatewayController@handleRequest');
$router->post('register', 'GatewayController@handleRequest');

// KINI NGA GROUP PARA SA MGA ROUTES NGA KINAHANGLAN NA OG TOKEN
$router->group(['middleware' => 'auth'], function () use ($router) {
    
    // Para sa Users (Site1)
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'users/{any:.*}', 'GatewayController@handleRequest');

    // Para sa Rentals (Site2)
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals', 'GatewayController@handleRequest');
    $router->addRoute(['GET', 'POST', 'PUT', 'DELETE'], 'rentals/{any:.*}', 'GatewayController@handleRequest');
});