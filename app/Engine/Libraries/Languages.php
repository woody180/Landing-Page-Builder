<?php

namespace App\Engine\Libraries;


class Languages {

    private static $path = APPROOT . "/Engine/Language_base/languages.json";

    private static function construct()
    {
        if (!isset($_SESSION['lang'])) {
            $path = APPROOT . "/Engine/Language_base/languages.json";
            if (file_exists($path)) {

                $languageList = json_decode(file_get_contents($path), true) ?? [];

                foreach($languageList as $lg) {
                    if ($lg['primary'] === true) {
                        $_SESSION['lang'] = $lg['code'];
                        break;
                    }
                }

            } else {

                die('You must set languages first');
            }
        }
    }


    // Translate by language file/s
    public static function translate($path) {

        self::construct();
        
        $lang = isset($_SESSION['lang']) ? strtolower($_SESSION['lang']) : 'defaults';
        
        if (is_array($path)) {
            if (isset($path[$lang]))
                return $path[$lang];
            else 
                return null;
        }
            
        
        $pathArr = explode('.', $path);

        $filePath = APPROOT . "/Languages/{$lang}/{$pathArr[0]}.php";
        $defaultPath = APPROOT . "/Languages/defaults/{$pathArr[0]}.php";
        
        if (file_exists($filePath))
            $content = include(APPROOT . "/Languages/{$lang}/{$pathArr[0]}.php");
        elseif (file_exists($defaultPath))
            $content = include(APPROOT . "/Languages/defaults/{$pathArr[0]}.php");

        return $content[end($pathArr)] ?? null;
    }



    // Switch languages
    public static function switch(string $langCode) {

        $path = APPROOT . "/Engine/Language_base/languages.json";   

        $languageList = json_decode(file_get_contents($path), true);
        $isTrue = false;

        foreach($languageList as $lg) {
            if ($lg['code'] === $langCode) {
                $isTrue = true;
                break;
            }
        }
        
        if ($isTrue) $_SESSION['lang'] = $langCode;
    }

    

    // Languages list
    public static function list() {
        
        self::construct();

        $path = APPROOT . "/Engine/Language_base/languages.json";

        $languageList = json_decode(file_get_contents($path));

        return $languageList;
    }


    // Active languages
    public static function primary() {
        
        self::construct();

        $languageList = json_decode(file_get_contents(self::$path));

        $activeLang = null;

        foreach($languageList as $lg) {
            if ($lg->primary === true) {
                $activeLang = $lg;
                break;
            }
        }

        return $activeLang;
    }


    public static function active()
    {
        self::construct();
        
        if (!isset($_SESSION['lang'])) {
            foreach (self::list() as $lang) {
                if ($lang->primary) return $lang->code;
                else dd('No default language code has been found. You must provide default language close through CLI.');
            }
        }
        
        return $_SESSION['lang'] ?? null;
    }
}