<?php namespace App\Controllers;

use \R as R;

class SettingsController {
    

    public function index($req, $res)
    {
        return $res->render('admin/settings');
    }



    public function update($req, $res)
    {
        initModel('settings');
        
        $settings = R::findLast('settings');
        $settings->import($req->body());
        R::store($settings);
        
        setFlashData('success', "Settings updated successfully.");
        
        return $res->redirect(baseUrl("admin/settings"));
    }
    
}