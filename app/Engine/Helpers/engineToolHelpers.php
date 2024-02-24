<?php



function filter_string_polyfill(string $string): string
{
    $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
    return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
}




function str2url($str, $options = array()) {
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
    );
    
    // Merge options
    $options = array_merge($defaults, $options);
    
    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
        'ß' => 'ss', 
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
        'ÿ' => 'y',

        // Latin symbols
        '©' => '(c)',

        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 

        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',

        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
        'Ž' => 'Z', 
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z', 

        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
        'Ż' => 'Z', 
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',

        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );
    
    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    
    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    
    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    
    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    
    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    
    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);
    
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}



function pager(array $params) {

    $currentSiteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $currentSiteUrl = explode('&page=', $currentSiteUrl)[0];

    if (strpos($currentSiteUrl, '?') && !strpos($currentSiteUrl, '?page')) {
        $currentSiteUrl = $currentSiteUrl . '&';
    } else if (strpos($currentSiteUrl, '?') && strpos($currentSiteUrl, '?page')) {
        $currentSiteUrl = explode('?page', $currentSiteUrl)[0] . '?';
    } else {
        $currentSiteUrl = $currentSiteUrl . '?';
    }

    $total = $params["total"] ?? die('Total page must provided as integer');
    $limit = $params["limit"] ?? die('Items by page must provided as integer');
    $currectPage = $params["current"] ?? die('Currennt page state must provided as integer');
    
    $outer = '<ul class="uk-pagination">%s</ul>';
    $inner = '';
    
    
    //get the last page number
    $last = ceil( $total / $limit );

    //calculate start of range for link printing
    $start = ( ( $currectPage - 2 ) > 0 ) ? $currectPage - 2 : 1;

    //calculate end of range for link printing
    $end = ( ( $currectPage + 2 ) < $last ) ? $currectPage + 2 : $last;
    
    
    // Previous page
    $currentLink = $currectPage > 1 ? $currectPage - 1 : $currectPage;
    $inner .= '<li><a href="'.$currentSiteUrl.'page='.$currentLink.'"><span uk-pagination-previous></span></a></li>';
    
    if ( $start > 1 ) {
        $inner .= '<li class="page-item"><a href="'.$currentSiteUrl.'page=1">1</a></li>';
        $inner .= '<li class="page-item uk-disabled"><a href="#"><span>...</span></a></li>';
    }
    
    for ($i = $start ; $i <= $end; $i++) {
        if ($currectPage == $i) {
            $inner .= '<li class="uk-active"><a href="'.$currentSiteUrl.'page='.$i.'">'.$i.'</a></li>';    
        } else {
            $inner .= '<li><a href="'.$currentSiteUrl.'page='.$i.'">'.$i.'</a></li>';    
        }
        
    }
    
    if ( $end < $last ) { //print ... before next page (>>> link)
        $inner .= '<li class="page-item uk-disabled"><a href="#"><span>...</span></a></li>';
        $inner .= '<li class="page-item"><a href="'.$currentSiteUrl.'page='.$last.'">'.$last.'</a></li>';
    }
    
    // Next page
    $nextLink = $currectPage < $last ? $currectPage + 1 : $currectPage;
    $inner .= '<li><a href="'.$currentSiteUrl.'page='.$nextLink.'"><span uk-pagination-next></span></a></li>';
    
    
    return sprintf($outer, $inner);
}




function rrmdir($dir) { 
    if (is_dir($dir)) { 
        $objects = scandir($dir);
        foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
            if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                rrmdir($dir. DIRECTORY_SEPARATOR .$object);
            else
                unlink($dir. DIRECTORY_SEPARATOR .$object); 
            } 
        }
        rmdir($dir); 
    } 
}


function isJSON($string){
    return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}



function toJSON($fileArray) {
    $json = json_encode($fileArray, JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    return $json;
}



function toArray($array) {
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = toArray($value);
            }
            if (is_object($value)) {
                $array[$key] = toArray((array)$value);
            }
        }
    }
    if (is_object($array)) {
        toArray((array)$array);
    }

    return $array;
}




function orderArrayByArray(array $array, array $order) {
    static $newArray = [];
    foreach ($order as $index => $id) {
        foreach ($array as $key => $item) {
            if ($item->id == $id)
                $newArray[$index] = $item;
        }
    }
    return array_reverse($newArray);
}



function array_search_index(array $products, string $field, string $value, $index = true) {
    foreach($products as $key => $product) {
        if (is_array($product)) if ( $product[$field] == $value ) return $index ? $key : 1;
        if (is_object($product)) if ( $product->{$field} == $value ) return $index ? $key : 1;
    }
    return false;
}



function array_value_multisort(&$array, $key, $nextArrayKey) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        if (is_array($va[$nextArrayKey])) {
            array_value_multisort($va[$nextArrayKey], $key, $nextArrayKey);
        }
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);

    foreach ($sorter as $ii => $va) {
        if (is_array($array[$ii][$nextArrayKey])) {
            array_value_multisort($array[$ii][$nextArrayKey], $key, $nextArrayKey);
        }
        $ret[$ii]=$array[$ii];
    }
    $array = $ret;
}



function set_cookie(array $data) {

    $tobeStored = [
       'name' => $data['name'] ?? "",
       'value' => isset($data['value']) ? toJSON($data['value']) : '',
       'expire' => isset($data['expire']) ? time() + $data['expire'] : time() + 86400,
       'path' => $data['path'] ?? '/',
       'domain' => $data['domain'] ?? "",
       'secure' => $data['secure'] ?? false,
       'httponly' => $data['httponly'] ?? false,
    ];
    
    setcookie($tobeStored['name'], $tobeStored['value'], $tobeStored['expire'], $tobeStored['path'], $tobeStored['domain'], $tobeStored['secure'], $tobeStored['httponly']);
}



function get_cookie(string $name) {

    if (isset($_COOKIE[$name])) {
        return json_decode($_COOKIE[$name]);
    } else {
        return false;
    }
}



function delete_cookie(string $name) {

    if (isset($_COOKIE[$name])) setcookie($name, '', time() - 3600, '/');
    return true;
}



function setFlashData(string $key, $data) {

    set_cookie([
        'name' => $key,
        'value' => $data
    ]);
}



function hasFlashData(string $key) {
    if (isset($_COOKIE[$key]))
        return true;

    return false;
}



function getFlashData(string $key) {

    $flashData = null;

    if (get_cookie($key)) {
        $flashData = get_cookie($key);
        delete_cookie($key);
    }
    
    return $flashData;
}



function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}




function clearCache() {
    array_map('unlink', array_filter((array) glob(APPROOT . "/Cache/*.txt")));
    return "All cached files cleared successfully.";
}