<?php

session_start();

// Configurations
require_once dirname(__DIR__) . '/Config/urls.php';
require_once APPROOT . '/Config/database.php';
require_once APPROOT . '/Config/app.php';
require_once APPROOT . '/Config/routes.php';
require_once APPROOT . '/Config/helpers.php';
require_once APPROOT . '/Config/boot.php';
require_once APPROOT . '/Config/css_compiler.php';
require_once APPROOT . '/Config/mail.php';

// Display errors
if (ERROR_HANDLING) 
    ini_set('display_errors', 1);
else
    ini_set('display_errors', 0);


// Base helper files
require_once APPROOT . "/Engine/Helpers/engineToolHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineDebuggingHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineUrlHelpers.php";
require_once APPROOT . "/Engine/Helpers/engineFormHelpers.php";


// Composer autoload
if (!file_exists(dirname(APPROOT) . '/vendor/autoload.php')) die('Composer autoload.php file not found!');
require_once dirname(APPROOT) . '/vendor/autoload.php';


// Helper library
if (!empty(CUSTOM_HELPERS)) {
    foreach (CUSTOM_HELPERS as $helperFile) {
        if (!file_exists(APPROOT . "/Helpers/{$helperFile}.php")) die("Wrong helper file path for - <b>{$helperFile}.php</b>");

        require_once APPROOT . "/Helpers/{$helperFile}.php";
    }
}


// RedBeanPHP model initialization function
if (DATABASE) require_once APPROOT . "/Engine/Database/Initialization.php";


// Load files on application boot
foreach (AUTOBOOT_FILES as $file) require_once APPROOT . "/Boot/{$file}.php";


// Multilingual
if (MULTILINGUAL) \App\Engine\Libraries\LanguageLogic::start();


// CSRF Protection
if (CSRF_PROTECTION) {
    if (!isset($_SESSION['csrf_token'])) {
        if (!CSRF_REFRESH) {
            if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

// Redirect to HTTPS
if (FORCE_SECURE_REQUESTS) {
    if(strtolower($_SERVER['REQUEST_SCHEME']) != 'https') {
        $server     = $_SERVER;
        $hostname   = "https://{$server['HTTP_HOST']}";
        $reqUri     = $server['REQUEST_URI'];
        $fullUrl    = $hostname . $reqUri;
        return header('Location: ' . $fullUrl);
    }
    if (preg_match('/www/', $_SERVER['HTTP_HOST'])) return header("Location: " . URLROOT);
}

$classes = glob(APPROOT . '/Routes/*.php');
foreach ($classes as $class) {
    $classPath = explode(APPROOT, $class)[1];
    $className = pathinfo($classPath)['filename'];
    include APPROOT . "$classPath";
}

// Additional route files
foreach (ROUTES_PATH as $path) {
    $dir = APPROOT . "/Routes/{$path}";
    if (is_dir($dir)) {
        $classes = glob(APPROOT . "/Routes/{$path}/*.php");
        foreach ($classes as $class) {
            $classPath = explode(APPROOT, $class)[1];
            $className = pathinfo($classPath)['filename'];
            include APPROOT . "$classPath";
        }
    }
}