<?php

use App\Engine\Libraries\Router;


$router = Router::getInstance();

$router->get('page/new', 'PageController@new', ['Middlewares/checkAdmin']);
$router->post('page', 'PageController@create', ['Middlewares/checkAdmin']);
$router->get('pages', 'PageController@index', ['Middlewares/checkAdmin']);
$router->get('page/(:segment)', 'PageController@show');
$router->get('page/(:segment)/edit', 'PageController@edit', ['Middlewares/checkAdmin']);
$router->put('page/(:segment)', 'PageController@update', ['Middlewares/checkAdmin']);
$router->patch('page/(:segment)', 'PageController@update', ['Middlewares/checkAdmin']);
$router->delete('page/(:segment)', 'PageController@delete', ['Middlewares/checkAdmin']);