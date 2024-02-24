<?php namespace App\Controllers;

use \R as R;
use stdClass;

class HomeController {
    
    public function index($req, $res) {

        helpers(['sectionDecoder']);
        
        $pageModel = initModel('pages');
        $page = $pageModel->home() ?? abort();
        $sections = initModel('section')->getSections($page->id);
        $decodedBodies = sectionDecoder($sections);
        
        // Render view
        return $res->render('welcome', [
            'title' => 'Cloudbet delivers a next-level experience',
            'page' => $page,
            'section' => $decodedBodies
        ]);
    }
}