<?php
        
class Model_Users extends RedBean_SimpleModel {

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

    public function migrate() {

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
        //////////////////////////////////////////////////

        $lastOne = R::findLast('usergroups');
        R::trash($lastOne);

        $lastOne = R::findLast('users');
        R::trash($lastOne);
    }
}