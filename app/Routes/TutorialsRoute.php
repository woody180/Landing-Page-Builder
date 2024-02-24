<?php

use App\Engine\Libraries\Router;


$router = Router::getInstance();

$router->get('admin/tutorials', function($req, $res)
{
    // Getting all videos
    $files = glob(dirname(APPROOT) . '/public/assets/tinyeditor/filemanager/files/tutorials/*.mp4');

    $res->render('admin/tutorials/videos', [
        'videos' => $files
    ]);

}, ['Middlewares/checkAdmin']);