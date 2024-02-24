<?php

use App\Engine\Libraries\Router;


$router = Router::getInstance();

$router->get('users', 'Users/UsersController@index', 'Middlewares/checkLogout');
//$router->get('users/reset', 'Users/UsersController@reset', 'Middlewares/checkLogout');
$router->get('users/register', 'Users/UsersController@registerView', 'Middlewares/checkLogout');
$router->post('users/register', 'Users/UsersController@register', 'Middlewares/checkLogout');

$router->get('users/login', 'Users/UsersController@loginView', 'Middlewares/checkLogout');
$router->post('users/login', 'Users/UsersController@login', 'Middlewares/checkLogout');

$router->get('users/account', 'Users/UsersController@accountView', 'Middlewares/checkUser');
$router->get('users/account/(:num)', 'Users/UsersController@accountView', 'Middlewares/checkUser');
$router->post('users/account/(:num)', 'Users/UsersController@account', 'Middlewares/checkUser');

$router->get('users/profile/(:num)', 'Users/UsersController@profile');
$router->get('users/logout', 'Users/UsersController@logout', 'Middlewares/checkUser');
$router->get('logout', 'Users/UsersController@logout', 'Middlewares/checkUser');

$router->match('get|post', 'users/reset', 'Users/UsersController@reset', 'Middlewares/checkLogout');