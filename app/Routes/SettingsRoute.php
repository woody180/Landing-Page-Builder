<?php

use App\Engine\Libraries\Router;

initModel('settings');

$router = Router::getInstance();

$router->get('admin/settings', 'SettingsController@index', ['Middlewares/checkAdmin']);
$router->put('admin/settings', 'SettingsController@update', ['Middlewares/checkAdmin']);
        