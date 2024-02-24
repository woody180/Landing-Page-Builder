<?php
        
class Model_Datainstaller extends RedBean_SimpleModel {

    public function open() {
        
    }

    public function dispense() {
        
    }

    public function update() {
        
    }

    public function after_update() {
        
    }

    public function delete() {
        
    }

    public function after_delete() {
        
    }
    
    
    
    public function migrate()
    {
        $page = R::dispense('page');
        $page->import([
            'title' => 'Welobet',
            'url' => '/',
            'thumbnail' => NULL,
            'description' => 'Welobet - landing page builder',
            'body' => NULL,
            'metadescription' => 'Welobet - landing page builder',
            'metakeywords' => 'one, two, three, four',
        ]);
        
        $id = R::store($page);
        $page = R::load('page', $id);
        
        
        $sections = R::dispense('section');
        $sections->import([
            'body' => '[{
                "title": "CONTENT BUILDING AUDIENCE",
                "image": "",
                "content": "Est perspiciatis explicabo possimus dolor aliquam ipsum commodi laborum odit. Impedit, quas nihil omnis illo nam deleniti accusantium rem dolores modi magni?",
                "background": "banner/banner-artwork.png"
            }]',
            'ordering' => 1,
            'show' => 1
        ]);
        $sections->page = $page; // Create many to one relationship when $page can have multiple section and seciton can have only one page
        R::store($sections);
    }
}