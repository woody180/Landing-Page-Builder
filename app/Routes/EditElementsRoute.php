<?php

use App\Engine\Libraries\Router;
use \R as R;

$router = Router::getInstance();



// ACCORDION
$router->get('load-editable-element/accordion', function($req, $res)
{
    $model = initModel('section');

    $pageurl = query('pageurl') === '/' ? '' : query('pageurl');

    // Find page
    $page = R::findOne('page', 'url = ?', [$pageurl]);

    if (!$page) return $res->status(404)->send(['error' => 'Page not found']);
    
    // Get accordion items
    $faq = $model->getPageRelatedElement($page->id, 'faq', false);

    if (empty($faq)) return $res->status(404)->send(['error' => 'Element edit file is not found.']);
    
    // Send items back to front
    return $res->render('admin/elementEdit/accordion', [
        "data" => json_decode($faq->body)->items,
        "id" => $faq->id
    ]);
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);





// SLIDER
$router->get('load-editable-element/slider', function($req, $res)
{
    $model = initModel('section');

    $pageurl = query('pageurl') === '/' ? '' : query('pageurl');

    // Find page
    $page = R::findOne('page', 'url = ?', [$pageurl]);

    if (!$page) return $res->status(404)->send(['error' => 'Page not found']);
    
    // Get accordion items
    $partners = $model->getPageRelatedElement($page->id, 'partners', false);

    if (empty($partners)) return $res->status(404)->send(['error' => 'Element edit file is not found.']);


    // Send items back to front
    return $res->render('admin/elementEdit/logo-slider', [
        "data" => json_decode($partners->body)->items,
        "id" => $partners->id
    ]);
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);




// SLIDESHOW
$router->get('load-editable-element/slideshow', function($req, $res)
{
    $model = initModel('section');

    $pageurl = query('pageurl') === '/' ? '' : query('pageurl');

    // Find page
    $page = R::findOne('page', 'url = ?', [$pageurl]);

    if (!$page) return $res->status(404)->send(['error' => 'Page not found']);
    
    // Get accordion items
    $slideshow = $model->getPageRelatedElement($page->id, 'slideshow', false);

    if (empty($slideshow)) return $res->status(404)->send(['error' => 'Element edit file is not found.']);


    // Send items back to front
    return $res->render('admin/elementEdit/slideshow', [
        "data" => json_decode($slideshow->body)->images,
        "id" => $slideshow->id
    ]);

    return $res->send([$slideshow]);
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);
        