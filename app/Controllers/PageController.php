<?php namespace App\Controllers;

use \R as R;
use Cocur\Slugify\Slugify;
use stdClass;

class PageController {
    
    // Add new view
    public function new($req, $res)
    {
        return $res->render("admin/pages/new");
    }


    // Create view
    public function create($req, $res)
    {
        $slugify = new Slugify();
        
        $page = R::dispense('page');
        $page->import($req->body());
        $page->url = $slugify->slugify($req->body('title'));
        
        if (R::store($page)) setFlashData('success', 'Page create successfully.');
        else setFlashData('error', 'Error occured while creating page.');
        
        return $res->redirect(baseUrl('pages'));
    }


    // All items
    public function index($req, $res)
    {
        $model = initModel('pages');
        
        return $res->render("admin/pages/pages", [
            "data" => $model->allPages()
        ]);
    }


    // Show view
    public function show($req, $res)
    {
        helpers(['sectionDecoder']);
        
        $url = $req->getSegment(2);
        $pageModel = initModel('pages');
        $page = $pageModel->find($url);

        $sections = initModel('section')->getSections($page->id);
        $decodedBodies = sectionDecoder($sections);

        return $res->render('page', [
            'page' => $page,
            'section' => $decodedBodies
        ]);
    }


    // Edit view
    public function edit($req, $res)
    {
        $id = $req->getSegment(2);
        $model = initModel('pages');

        return $res->render("admin/pages/edit", [
            "page" => $model->getPage($id)
        ]);
    }


    // Update
    public function update($req, $res)
    {
        $id = $req->getSegment(2);
        initModel('pages')->save($id, $req->body());
        setFlashData('success','Page saved successfully.');
        return $res->redirect(baseUrl("page/{$id}/edit"));
    }


    // Delete
    public function delete($req, $res)
    {
        R::trash(R::load('page', $req->getSegment(2)));
        setFlashData('message', 'Page removed successfully.');
        return $res->redirect(baseUrl('pages'));
    }

}
        