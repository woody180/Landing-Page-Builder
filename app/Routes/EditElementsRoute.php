<?php

use App\Engine\Libraries\Router;
use \R as R;

$router = Router::getInstance();



// ACCORDION
$router->get('load-editable-element/accordion', function($req, $res)
{
    $model = initModel('section');
    
    // Find page
    $page = initModel('pages')->home();
    
    // Get accordion items
    $faq = $model->getSection($page->id, 'faq', false);
    
    
    // Send items back to front
    // return $req->send(["section_id" => $faq->id, "data" => json_decode($faq->body)->items]);
    return $res->render('admin/elementEdit/accordion', [
        "data" => json_decode($faq->body)->items,
        "id" => $faq->id
    ]);
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);





// SLIDER
$router->get('load-editable-element/slider', function($req, $res)
{
    $model = initModel('section');

    // Find page
    $page = initModel('pages')->home();
    
    // Get accordion items
    $slider = $model->getSection($page->id, 'partners', false);
    
    
    // Send items back to front
    // return $req->send(["section_id" => $faq->id, "data" => json_decode($faq->body)->items]);
    return $res->render('admin/elementEdit/slider', [
        "data" => json_decode($slider->body)->items,
        "id" => $slider->id
    ]);
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);

        