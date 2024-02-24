<?php
        
class Model_Section extends RedBean_SimpleModel {

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
    

    protected function applyChanges(&$array, $keys, $newValue)
    {
        $temp = &$array;
        foreach ($keys as $key) $temp = &$temp[$key];
        foreach ($newValue as $i => $val) $temp[$i] = $val;
    }

    
    
    public function getSection($pageID, $id, $json = TRUE)
    {
        $data = null;
        if (is_numeric($id)) {
            $data = R::findOne('section', 'id = ?', [$id]) ?? abort(["code" => 404, "text" => "section can't be found"]);
        } else {
            $data = R::findOne('section', 'title = ?', [$id]) ?? abort(["code" => 404, "text" => "section can't be found"]);
        }        
        
        if ($json) return json_decode($data->body);
        return $data;
    }
    

    public function getSectionElement($pageID, $sectionID, $alias)
    {
        $section = R::findOne('section', 'id = ?', [$sectionID]);
        $aliasArr = explode('.', $alias);

        function getSectionEl(&$array, $keys) {
            $temp = &$array;
            foreach ($keys as $key) $temp = &$temp[$key];
            return $temp;
        }
        $bodyArr = json_decode($section->body, true);
        array_splice($aliasArr, 0, 3);
        
        return getSectionEl($bodyArr, $aliasArr);
    }
    

    public function getSectionByName(string $sectionName)
    {
        return R::findOne("section", "title = ?", [$sectionName]) ?? abort();
    }
    
    public function getSections($pageID)
    {
        $arr = [];
        $data = R::findOne('page', 'id = ?', [$pageID]);
        foreach ($data->sharedSection as $item) $arr[$item->title] = (object)$item;
        return (object)$arr;
    }


    public function saveSection($params, $content)
    {
        $structure = explode('.', $params);
        $table = $structure[0];
        $id = $structure[1];
        $row = $structure[2];

        array_splice($structure, 0,3);

        $sectionBody = R::findOne('section', 'id = ?', [$id]);
        $sectionBodyArray = json_decode($sectionBody->{$row}, true); // Array where you must apply changes.

        $this->applyChanges($sectionBodyArray, $structure, $content);

        $sectionBody->{$row} = toJSON($sectionBodyArray);
        return R::store($sectionBody);
    }
}