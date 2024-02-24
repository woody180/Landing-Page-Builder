<?php
use \R as R;

function initModel(string $modelName) {

    $modelName = ucfirst($modelName);

    if (DATABASE) {

        require_once APPROOT . '/Engine/Libraries/rb.php';
        require_once APPROOT . '/Engine/Database/Connection.php';

        if (!is_array($modelName) && file_exists(APPROOT . "/Models/Model_$modelName.php")) {
            
            \App\Engine\Database\Connection::getInstance();

            require_once APPROOT . "/Models/Model_$modelName.php";

            return R::dispense(strtolower($modelName));
        }
    }

    return false;
}