<?php namespace App\Engine\Libraries;

class Router {
    
    use \App\Engine\Libraries\RequestResponseTrait\RequestTrait, \App\Engine\Libraries\RequestResponseTrait\ResponseTrait;
    
    private $routes = [];
    private $request;
    private static $instance = null;
    private $currentController;
    private $currentMethod;
    private $viewPath;

    
    private function __construct() {
        $this->request = $this->getRequest();
    }
    
    
    private function parseArgs(string $method, $arg, $callback, $middleware = null)
    {
        if (is_array($arg)) {
            $cntrlr = $arg['controller'];
            $mtd    = $arg['method'] ?? 'index';
            $md     = $arg['middleware'] ?? '';
            
            $contollerWithMethld = ucfirst($cntrlr) . '@' . $mtd; // Bind contorller with method like this - 'Controller@method'
            $this->routes[$method][$arg['route']] = [$contollerWithMethld, $md, 'csrf' => $arg['csrf'] ?? CSRF_PROTECTION]; // Uppend to the routes array
        } else {
            $this->routes[$method][$arg] = [$callback, $middleware, 'csrf' => $arg['csrf'] ?? CSRF_PROTECTION];
        }
    }


    public static function getRoutes()
    {
        return self::$instance->routes;
    }




    // Router HTTP verbs
    public function get($url, $callback = null, $middleware = null) {
        $this->parseArgs('get', $url, $callback, $middleware);
    }
    public function post($url, $callback = null, $middleware = null) {
        $this->parseArgs('post', $url, $callback, $middleware);
    }
    public function put($url, $callback = null, $middleware = null) {
        $this->parseArgs('put', $url, $callback, $middleware);
    }
    public function patch($url, $callback = null, $middleware = null) {
        $this->parseArgs('patch', $url, $callback, $middleware);
    }
    public function delete($url, $callback = null, $middleware = null) {
        $this->parseArgs('delete', $url, $callback, $middleware);
    }
    public function match($methods, $url, $callback, $middleware = null) {

        $methodsArray = explode('|', $methods);

        foreach ($methodsArray as $method)
            $this->routes[$method][$url] = [$callback, $middleware];
    }
    public function all($url, $callback, $middleware = null) {

        $httpVerbs = ['get', 'post', 'put', 'patch', 'delete', 'options'];

        foreach ($httpVerbs as $verb)
            $this->routes[$verb][$url] = [$callback, $middleware];
    }
    
    
        
    // Placeholder to regex
    protected function checkPatternMatch() {
        $routes = [];

        // dd($this->routes[$this->request->getMethod()]);

        foreach ($this->routes[$this->request->getMethod()] as $route => $method) {
            // Check if route has comma in it
            if (strpos($route, ',')) {

                // Explode route
                $newRoutes = array_map('trim', explode(',', $route));

                // Remove from route array
                unset($this->routes[$this->request->getMethod()][$route]);

                // Add new routes to $this->routes array
                foreach ($newRoutes as $newRoute) $this->routes[$this->request->getMethod()][$newRoute] = $method;
            }
        }

        foreach ($this->routes[$this->request->getMethod()] as $route => $method) {

            $url = str_replace('/', '\/', $route);
            $url = str_replace('(:continue)', '[\p{L}\w\-_].*', $url);          // Continues segment
            $url = str_replace('(:num)', '\d+', $url);                          // Only numbers
            $url = str_replace('(:alpha)', '[\p{L}]+', $url);                   // Only alphabetical
            $url = str_replace('(:alphanum)', '[\p{L}\d]+', $url);              // Only alphabetical and numbers
            $url = str_replace('(:segment)', '[\w\-_]+', $url);                 // Only alpha, num, dashes, lowdashes and numbers
            $url = str_replace('(:any)', '.*', $url);

            // Push to new routes array
            $routes[$this->request->getMethod()][$url] = $method;
        }
        
        // Find requested url
        foreach ($routes[$this->request->getMethod()] as $route => $method)
        {
            $queryStr = !empty($this->request->query()) ? $this->request->queryStr() : null;
            $compareTo = $queryStr ? explode($queryStr, $this->request->url())[0] : $this->request->url();
            $compareTo = empty($compareTo) ? '/' : $compareTo;
            
            if (preg_match("/".$route."/mu", $compareTo, $match)) {

                if (isset($match[0]) && $match[0] === $compareTo) {
                    
                    if (isset($method['csrf']) && $method['csrf']) $this->checkCSRF();
                    
                    return $method;
                } else {
                    continue;
                }
            }
        }
        
        return 0;
    }
    
    
    private function runMiddleware($func) {
        
        // Check if array
        if (is_array($func))
        {
            foreach ($func as $md) {
                // Check if file exists
                $file = APPROOT . "/Routes/{$md}.php";
                
                if (!file_exists($file)) die('Wrong middleware path: <strong><mark>' . $md . '</mark></strong>' );
                
                // check slashes
                require_once $file;
                
                $arr = explode('/', $md);
                $function = end($arr);

                $function($this->getRequest(), $this->getResponse());
            }
        } else {
            // Check if file exists
            $file = APPROOT . "/Routes/{$func}.php";

            if (!file_exists($file)) die('Wrong middleware path: <strong><mark>' . $func . '</mark></strong>' );

            // check slashes
            require_once $file;

            $arr = explode('/', $func);
            $function = end($arr);

            $function($this->getRequest(), $this->getResponse());
        }
    }


    // File namespace extractor
    protected function getNamespaceByFileContent ($src) {
        $tokens = token_get_all($src);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < $count) {
                    if ($tokens[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }
                break;
            }
            $i++;
        }
    
        if (!$namespace_ok) {
            return null;
        } else {
            return $namespace;
        }
    }
    
    
    public function __destruct() {
        
        // Check if method exists inside the router
        if (array_key_exists($this->request->getMethod(), $this->routes)) {

            // Check if user exists inside the routes method
            
            if ($this->checkPatternMatch()) {
                
                // Get callback variable
                $callback = $this->checkPatternMatch();
                
                // Check if callback variable is statusCode
                if (is_numeric($callback[0])) abort(['code' => $callback[0]]);
                unset($_POST['csrf_token']);
                
                
                // Get url segments as array with request & response traits 
                $urlSegmentsWtihReqResTrait = $this->urlSegments() ?? [];
                array_unshift($urlSegmentsWtihReqResTrait, $this->getRequest());
                array_unshift($urlSegmentsWtihReqResTrait, $this->getResponse());
                
                // Check if $callback is callable
                if (is_callable($callback[0])) {

                    // Check if route has some middleware
                    if ($callback[1]) $this->runMiddleware($callback[1]);

                    call_user_func_array($callback[0], $urlSegmentsWtihReqResTrait);

                } else {
                    
                    // Controller & method array
                    $controllerMethodArray = explode('@', $callback[0]);

                    // Get controller
                    $this->currentController = $controllerMethodArray[0];

                    // Check if controller file exists
                    if (!file_exists(APPROOT . "/Controllers/{$this->currentController}.php"))
                        abort();

                    // Require controller file
                    require_once APPROOT . "/Controllers/{$this->currentController}.php"; // Include file
                    $src = file_get_contents(APPROOT . "/Controllers/{$this->currentController}.php"); // Extract file

                    // Instantiate controller
                    $controller = explode('/', $this->currentController);
                    $this->currentController = end($controller);
                    $checkNamespace = $this->getNamespaceByFileContent($src);

                    // Check if namespace is inside the controller
                    if (!$checkNamespace) die('Controller must has a namespace');

                    // Get controller namespace
                    $namespace = $checkNamespace . '\\' . $this->currentController;

                    // Initialize controller
                    $this->currentController = new $namespace();

                    // Get method
                    $this->currentMethod = $controllerMethodArray[1];
                    
                    // Check method inside the controller
                    if (!method_exists($this->currentController, $this->currentMethod)) abort();
                    
                    // Check if route has some middleware
                    if ($callback[1]) $this->runMiddleware($callback[1]);

                    // Call method and apply arguments
                    call_user_func_array([$this->currentController, $this->currentMethod], $urlSegmentsWtihReqResTrait);

                }
            } else {

                abort();
            }

        } else {

            abort();
        }
    }
    
    public static function getInstance() {
        if (!self::$instance) self::$instance = new Router();
        
        return self::$instance;
    }
}