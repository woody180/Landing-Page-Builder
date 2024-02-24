<?php

use App\Engine\Libraries\Router;


$router = Router::getInstance();



// ACCORDION
$router->get('elements/accordion', function($req, $res) {

    helpers(['sectionDecoder']);
   
    $pageModel = initModel('pages');
    $page = $pageModel->home();
    $sectoins = initModel('section')->getSections($page->id);
    
    return $res->render("elements/accordion", [
        "section" => sectionDecoder($sectoins)
    ]);
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);





// SLIDER
$router->get('elements/slider', function($req, $res) {

    helpers(['sectionDecoder']);
   
    $pageModel = initModel('pages');
    $page = $pageModel->home();
    $sectoins = initModel('section')->getSections($page->id);
    
    return $res->render("elements/slider", [
        "section" => sectionDecoder($sectoins)
    ]);
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);