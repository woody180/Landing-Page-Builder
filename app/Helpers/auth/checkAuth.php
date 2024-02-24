<?php

function checkAuth(array $privilegies = []) {

    $id = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
    $user = null;

    if ($id) {

        $user = R::findOne('users', 'id = ?', [$id]);

        if (!empty($privilegies)) {
            if (in_array($user->usergroups->id, $privilegies))
                return true;

            return false;
        }

        if (!$user) {
            unset($_SESSION['userid']);
            return false;
        } else {
            return TRUE;
        }
    }

    return false;
    
}