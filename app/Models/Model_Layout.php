<?php
        
class Model_Layout extends RedBean_SimpleModel {

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



    public function layoutList() {
        return R::find("layout", "order by id DESC");
    }
}