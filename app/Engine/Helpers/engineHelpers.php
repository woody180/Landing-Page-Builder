<?php

function helpers(array $arrayPaths) {

    foreach ($arrayPaths as $path) {
        if (!file_exists(APPROOT . "/Helpers/{$path}.php")) die("Wrong helper file path for - <b>{$path}.php</b>");

        require_once APPROOT . "/Helpers/{$path}.php";
    }
}