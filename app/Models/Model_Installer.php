<?php
        
class Model_Installer extends RedBean_SimpleModel {

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
        R::nuke(); // Remove all tables
        
        // settings
        $settings = R::dispense("settings");
        $settings->import([
            'title' => 'title',
            'primarycolor' => 'primarycolor',
            'secondarycolor' => 'secondarycolor',
            'description' => 'description',
            'keywords' => 'keywords',
            'logo' => 'logo',
            'favicon' => 'fav',
            'containerwidth' => 'width',
            'facebookurl' => 'facebook',
            'instagramurl' => 'instagram',
            'youtubeurl' => 'youtube',
            'twitterurl' => 'twitter'
        ]);
        R::store( $settings );



        // Pages
        $page = R::dispense('page');
        $page->import([
            'title' => 'Welcome page',
            'url' => '',
            'thumbnail' => '',
            'description' => 'Welcome to landing page builder',
            'body' => '',
            'metadescription' => '',
            'metakeywords' => ''
        ]);

        // Sections
        $section = R::dispense('section');
        $section->import([
            'title' => 'title',
            'body' => '{"title": "ADDING NEW TITLE","description": "This is my new description"}',
            'ordering' => 'ordering',
            'show' => 'show',
            'classes' => 'classes',
            'identifier' => 'identifier',
            'background' => 'background',
            'color' => 'color',
            'text_color' => 'text_colors'
        ]);

        // Created shared intense
        $section->sharedPagesList[] = $page;

        R::store( $section );


        // Layouts
        $layout = R::dispense('layout');
        $layout->name = 'layout_name';
        $page = R::findLast('page');
        $layout->ownPageList[] = $page;
        R::store($layout);



        // USERS //
        //////////////////////////////////////////////////
        $groupsArr = [
            [
                'name' => 'Super user',
                'description' => '',
            ],
            [
                'name' => 'Manager',
                'description' => '',
            ],
            [
                'name' => 'Registered',
                'description' => '',
            ],
            [
                'name' => 'Guest',
                'description' => '',
            ]
        ];
        foreach ($groupsArr as $key => $val) {
            $groups = R::dispense('usergroups');
            $groups->import($val);
            R::store($groups);
        }
        //////////////////////////////////////////////////



        //////////////////////////////////////////////////
        $users = R::dispense('users');
        $users->import([
            'name' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'avatar' => '',
            'vkey' => '',
            'activated' => '',
            'createdat' => 11111111
        ]);
        $usergroups = R::dispense('usergroups');
        $users->usergroups = $usergroups;
        R::store($users);
        $lastOne = R::findLast('usergroups');
        R::trash($lastOne);
        //////////////////////////////////////////////////



        // Clean table data
        //R::wipe('page');
        R::wipe('section');
        R::wipe('settings');
        R::wipe('users');
    }
}