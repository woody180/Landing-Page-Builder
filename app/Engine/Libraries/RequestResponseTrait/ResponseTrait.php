<?php

namespace App\Engine\Libraries\RequestResponseTrait;

trait ResponseTrait {
    
    private string $viewHtml;


    public function getResponse() {
        return $this;
    }
    
    
    // Respond as JSON
    public function send($obj) {
        header("Content-Type: application/json; charset=UTF-8");
        echo toJSON($obj);
    }
    
    
    // Response code
    public function status(int $response_code) {
        http_response_code($response_code);
        return $this;
    }
    
    
    public function getCached(string $cacheFileName) {

        if (!CACHE_ENABLED) return FALSE;
        
        $dirPath = APPROOT . "/Cache";
        $arr = glob("{$dirPath}/{$cacheFileName}_*.txt");
        
        if (!empty($arr)) {
            foreach ($arr as $name) {
                $filename = pathinfo($name)['filename'];
                $time_till_cache_expire = explode('_', $filename)[1];
                
                if( (time() - (int)$time_till_cache_expire) > 0 ) {
                    unlink("{$dirPath}/{$filename}.txt");
                } else {
                    $text = "";
                    if (strtolower(ENV) === 'development') $text = "<b>From cache:</b><br>";
                    
                    echo $text . file_get_contents("{$dirPath}/{$filename}.txt");
                    die;
                }
            }
        }
    }


    // Render veiw
    public function render(string $viewPath, array $arguments = []) {
        
        $this->viewPath = $viewPath;
        
        $templates = new \League\Plates\Engine(APPROOT . "/Views");
        $this->viewHtml = $templates->render($viewPath, $arguments);
        echo $this->viewHtml;
        
        return $this;
    }
    

    // Cache views
    public function cache(string $key, int $time = 20) {

        if (!CACHE_ENABLED) return FALSE;

        $timestamp = time() + $time;
        $cachePath = APPROOT . "/Cache/{$key}_{$timestamp}.txt";
        
        file_put_contents($cachePath, $this->viewHtml);
        
        return $this;
    }



    // Redirect
    public function redirect(string $url) {
        return header('Location: ' . $url);
    }


    // Redirect back
    public function redirectBack() {

        if (hasFlashData('previous_url'))
            return $this->redirect(URLROOT . "/" . getFlashData('previous_url'));
        else {
            return $this->redirect(URLROOT);
        }
    }
}
