<?php

use App\Engine\Libraries\Router;

$router = Router::getInstance();

$router->get('/', 'HomeController@index');
