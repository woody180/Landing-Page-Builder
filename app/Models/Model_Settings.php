<?php
        
class Model_Settings extends RedBean_SimpleModel {

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


    public function favicon()
    {
        return R::findLast('settings')->favicon ?? '';
    }
    
    
    public function getSettings(string $param = null) {
        
        if (is_null($param))
            return R::findOne('settings');
        else
            return R::findOne ('settings')->{$param};
    }
}