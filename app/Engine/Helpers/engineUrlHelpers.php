<?php

function urlExists($url) {
    $url_headers = @get_headers($url);
    if (strpos($url_headers[0],'200')) {
        return true;
    } else {
        return false;
    }
}


// Url segments
function urlSegments($index = null, bool $removeQuery = false) {
    
    $urlArr = explode(URLROOT, CURRENT_URL);
    $url = isset($urlArr[1]) ? $urlArr[1] : '/';
    $url = ltrim($url, '/');
    $url = empty($url) ? '/' : $url;

    if ($removeQuery) {
        if (preg_match('/\?/', $url, $matchQuery)) {
            list($path, $parameters) = explode('?', $url);
            $url = $path;
        }
    }
    
    if (!is_null($index) && !is_int($index)) {
        $option = strtolower($index);
        $urlSegments = explode('/', $url);
        
        if ($option === 'last') $url = end($urlSegments);
        if ($option === 'first') $url = reset($urlSegments);
    }

    if (is_int($index)) {
        $i = $index - 1;
        $url = explode('/', $url)[$i] ?? null;
    }
    
    if (is_null($url)) return null;
    preg_match('/([^\?]+)(\?.*)?/', $url, $match);
    $matchIndex = $removeQuery ? 1 : 0;
    $res = $match[$matchIndex] ?? null;
    return !$res ? null : urldecode($res);
}


function baseUrl(string $url = null, $withLanguageCode = false) {
    
    if (MULTILINGUAL) $withLanguageCode = true;
    
    if (MULTILINGUAL && $withLanguageCode) {
        if ($url)
            return URLROOT . '/' . \App\Engine\Libraries\Languages::active() . '/' . $url;
        else
            return URLROOT . '/' . \App\Engine\Libraries\Languages::active();
    }
    
    if ($url)
        return URLROOT . '/' . $url;
    else
        return URLROOT;
}


function assetsUrl(string $url = null) {

    $publicUrl = $url ? '/' . $url : '';
    return PUBLIC_DIR . $publicUrl;
}


function query(string $key = null) {
    // Query string
    preg_match_all('/[\?](.*)[\/]?+/', CURRENT_URL, $queryString);
    $queryStr = null;

    if ( isset($queryString[0]) && isset($queryString[0][0]) ) {
        parse_str($queryString[1][0], $queryArr);
        $queryStr = $queryString[0][0];
    } else {
        $queryArr = null;
    }

    if ($key) {
        if (isset($queryArr[$key])) {
            $res = htmlspecialchars($queryArr[$key], ENT_QUOTES);
            return empty($res) ? null : $res;
        }
        
        return null;
        
    } else {
        return $queryArr;
    }
}



function status($code = NULL) {

    $text = '';

    if ($code !== NULL) {
        switch ($code) {
            case 100: $text = 'Continue'; break;
            case 101: $text = 'Switching Protocols'; break;
            case 200: $text = 'OK'; break;
            case 201: $text = 'Created'; break;
            case 202: $text = 'Accepted'; break;
            case 203: $text = 'Non-Authoritative Information'; break;
            case 204: $text = 'No Content'; break;
            case 205: $text = 'Reset Content'; break;
            case 206: $text = 'Partial Content'; break;
            case 300: $text = 'Multiple Choices'; break;
            case 301: $text = 'Moved Permanently'; break;
            case 302: $text = 'Moved Temporarily'; break;
            case 303: $text = 'See Other'; break;
            case 304: $text = 'Not Modified'; break;
            case 305: $text = 'Use Proxy'; break;
            case 400: $text = 'Bad Request'; break;
            case 401: $text = 'Unauthorized'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 500: $text = 'Internal Server Error'; break;
            case 501: $text = 'Not Implemented'; break;
            case 502: $text = 'Bad Gateway'; break;
            case 503: $text = 'Service Unavailable'; break;
            case 504: $text = 'Gateway Time-out'; break;
            case 505: $text = 'HTTP Version not supported'; break;
            default:
                exit('Unknown http status code "' . htmlentities($code) . '"');
            break;
        }

        

        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' ' . $code . ' ' . $text);
        
        $GLOBALS['http_response_code'] = $code;
    } else {
        $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
    }

    return [
        "code" => $code,
        "text" => $text
    ];
}




function abort(array $params = ["code" => 404, "url" => NULL, "text" => NULL]) {
    $code = $params["code"] ?? 404;
    $url = $params["url"] ?? NULL;
    $text = $params["text"] ?? 'Sorry, but the page you are looking for is not found. Please make sure you have typed the correct url.';
    
    $status = status($params['code']);

    if (!$url) {
        $dom = '';
        
        $dom .= '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.status($params["code"])["code"] . ' ' . status($params["code"])["text"].'</title>
            <style>
                html, body {margin: 0; padding: 0; font-family: sans-serif;
                width: 100%; height: 100%; color: #606472}
                a {text-decoration: none; color: #2196f3;}
                h1, h2, h3 {font-size: 50px; color: #606472;
                    margin: 0; padding: 0; margin-bottom: 15px;}
                h4, h5, h6 {font-size: 30px; color: #606472;
                    margin: 0; padding: 0; margin-bottom: 15px;}
                p {line-height: 28px;}
                .text-large {font-size: 130px; margin-bottom: 0;}
                .container {
                    padding: 0 45px;
                    max-width: 1200px;
                    margin: auto;
                    height: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <center>
                    <div style="margin-bottom: 25px;">
                        <h1 class="text-large">'.$code.'</h1>
                        <h4>'.$status["text"].'</h4>
                    </div>

                    <div style="margin-bottom: 25px;">';
                    $dom .= '<p>'.$text.'</p>';
                    $dom .= '</div>
                    <a href="'.URLROOT.'">Back to home page &#8594;</a>
                </center>
            </div>
        </body>
        </html>';

        echo $dom;
        die();
    } else {
        require_once APPROOT . "/views/{$params['url']}.php";
        die();
    }

}