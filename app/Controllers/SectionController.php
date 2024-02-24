<?php namespace App\Controllers;

use \R as R;


class SectionController {
    
    // Add new view
    public function new($req, $res) {
        
    }


    // Create view
    public function create($req, $res) {
       
    }


    // All items
    public function index($req, $res)
    {
        $sections = R::getAll('SELECT id, title, show FROM section ORDER BY id ASC');
        return $res->render('admin/elementEdit/sectionsList', [
            'sections' => $sections
        ]);
    }


    // Show view
    public function show($req, $res)
    {
        $id = $req->getSegment(2);
        $model = initModel('section');
        $page = initModel('pages')->home();

        if (query('elpath'))
        {
            $alias = query('elpath');
            return $res->send($model->getSectionElement($page->id, $id, $alias));
        }
        return $res->send($model->getSection($page->id, $id, false));
    }


    // Edit view
    public function edit($req, $res) {
        $id = $req->getSegment(2);
    }


    // Update
    public function update($req, $res) {
        $id = $req->getSegment(2);
        

        if ($req->body('initial')) {
            $body = [
                'show' => $req->body('show')
            ];
        } else {
            $body = [
                'show' => $req->body('show') ?? NULL,
                'color' => $req->body('color') ?? NULL,
                'text_color' => $req->body('text_color') ?? NULL,
                'background' => $req->body('background') ?? NULL
            ];
        }
        
        
        $modal = initModel('section');
        $section = R::findOne('section', 'id = ?', [$id]);
        if (!$section) return $res->status(404)->send(['error' => 'section not found.']);
        $section->import($body);
        R::store($section);
        
        return $res->send(['success' => 'Settings saved successfully.']);
    }


    // Delete
    public function delete($req, $res) {
        $id = $req->getSegment(2);
    }

}
        