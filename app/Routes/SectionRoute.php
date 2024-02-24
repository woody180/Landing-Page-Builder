<?php

use App\Engine\Libraries\Router;


$router = Router::getInstance();

$router->get('section/new', 'SectionController@new');
$router->post('section', 'SectionController@create', ['Middlewares/checkAdmin']);
$router->get('section', 'SectionController@index', ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);
$router->get('section/(:segment)', 'SectionController@show');
$router->get('section/(:segment)/edit', 'SectionController@edit', ['Middlewares/checkAdmin']);
$router->put('section/(:segment)', 'SectionController@update', ['Middlewares/checkAdmin']);
$router->patch('section/(:segment)', 'SectionController@update', ['Middlewares/checkAdmin']);
$router->delete('section/(:segment)', 'SectionController@delete', ['Middlewares/checkAdmin']);
        