<?php



function filter_string_polyfill(string $string): string
{
    $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
    return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
}



// Converting string to url
function str2url(string $string) :string
{
    // Normalize the string to UTF-8
    $string = mb_convert_encoding($string, 'UTF-8');

    // Replace non-alphanumeric characters with spaces
    $string = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $string); // \p{L} for letters, \p{N} for numbers

    // Trim whitespace from the beginning and end
    $string = trim($string);

    // Replace spaces with hyphens
    $string = preg_replace('/\s+/', '-', $string);

    // Convert to lowercase
    $string = mb_strtolower($string, 'UTF-8');

    return $string;
}



function pager(array $params) {

    $currentSiteUrl = explode('&page=', CURRENT_URL)[0];

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
       'value' => isset($data['value']) ? compressData($data['value']) : '',
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
        return decompressData($_COOKIE[$name]);
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




function compressData($data) {
    $json_data = toJSON($data);
    $compressed_data = gzcompress($json_data);
    return base64_encode($compressed_data);
}

function decompressData($compressed_data) {
    $data = base64_decode($compressed_data);
    $json_data = gzuncompress($data);
    return json_decode($json_data);
}
