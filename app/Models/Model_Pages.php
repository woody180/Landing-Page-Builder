<?php
        
use Cocur\Slugify\Slugify;

class Model_Pages extends RedBean_SimpleModel {

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
    
    
    public function home()
    {
        return R::findOne('page', 'url = ?', ['']);
    }
    
    
    public function find(string $url)
    {
        return R::findOne('page', 'url = ?', [$url]) ?? abort();
    }


    public function getPage($id) {
        if (is_numeric($id)) return R::findOne('page', 'id = ?', [$id]) ?? abort();
        return R::findOne('page', 'url = ?', [$id]) ?? abort();
    }
    
    
    public function allPages()
    {
        $totalPages = R::count('page');
        $currentPage = $_GET["page"] ?? 1;
        if ($currentPage < 1 OR $currentPage > $totalPages) $currentPage = 1;
        $limit = 20;
        $offset = ($currentPage - 1) * $limit;  
        $pagingData = pager([
            'total' => $totalPages,
            'limit' => $limit,
            'current' => $currentPage
        ]); 
        $pages = R::find("page", "order by id desc limit $limit offset $offset");
        
        $obj = new stdClass();
        $obj->pager = $totalPages > $limit ? $pagingData : null;
        $obj->pages = $pages;
        
        return $obj;
    }


    public function save($id, $data)
    {
        $slugify = new Slugify();
        $data['url'] = $slugify->slugify($data['url']);
        $page = $this->getPage($id)->import($data);
        return R::store($page);
    }
}