<?php
/**
 * Description of Router
 *
 * @author alisson
 */

include 'Route.php';

class Router {
    
    protected static $requestUri = null;

    protected static $routes = array();
    
    protected static $mapPrefix = '/';

    protected static function __parseUri(){
        
        self::$requestUri = (isset($_GET['uri']) && !empty($_GET['uri']) && $_GET['uri'] != '/')
                ?filter_var(rtrim($_GET['uri'], '/'), FILTER_SANITIZE_STRING)
                :'/';
       
        unset($_GET['uri']);
        
    }
    
    protected function combineCallbackMatches($callback = array(), $matches = array()) {
        
        if(count($matches) > 1 && !is_callable($callback)){
            $lastMatch = end($matches);
            
                $lastMatch = explode('/', $lastMatch);
                foreach($lastMatch as $part){
                    $callback[] = $part;
                }
                      
        }
        return $callback;
    }
    
    protected static function __matchRequestMethod($requestMethod = 'GET'){
        
        if(in_array($_SERVER['REQUEST_METHOD'], array_map('trim', explode('|', $requestMethod)))){
            return true;
        }
        return false;
    }
        
    protected static function __defineModuleControllerAction($match = null){
        
        $parts = $newparts = array();
        
        if(!is_array($match)){
            $parts = explode('/', ltrim($match, '/'));
        }else{
            if(strpos($match[0], '/')){
                list($parts[0], $parts[1]) = explode('/', $match[0]);         
            }else{
               $parts[0] = $match[0];
            }            
            unset($match[0]);
            
            foreach($match as $param){
                $parts[] = $param;
            }            
        }

        if(isset($parts[1]) &&  is_file(BUSINESS_DIR . DS . ucfirst($parts[0]) . DS. ucfirst($parts[1]) . DS . ucfirst($parts[1]) . '.php')){
            $newparts[] = ucfirst($parts[0]) . '\\' . ucfirst($parts[1]);
            $newparts[] = isset($parts[2])?$parts[2]:'index';
            Core::set('module', ucfirst($parts[0]));
            Core::set('controller', ucfirst($parts[1]));
            Core::set('action',isset($parts[2])?$parts[2]:'index');
            unset($parts[0]);
            unset($parts[1]);
            unset($parts[2]);
        }
       
        else if(is_file(BUSINESS_DIR . DS . ucfirst($parts[0]) . DS . ucfirst($parts[0]) . '.php')){
            $newparts[] = ucfirst($parts[0]);
            $newparts[] = isset($parts[1])?$parts[1]:'index';
            Core::set('module', null);
            Core::set('controller', ucfirst($parts[0]));
            Core::set('action', isset($parts[1])?$parts[1]:'index');
            unset($parts[0]);
            unset($parts[1]);
        }else{
            exit('ERRO');
        }
               
        return array($newparts, $parts);
    }   
    
    protected static function __discoverController($controller){
        return array_reverse(array_merge(array(''), explode('/', $controller)));
    }
      
    protected static function __compareArgs($callback, $routeArgs) {
        $params = [];
        $ref = new ReflectionFunction($callback);
        foreach( $ref->getParameters() as $param) {
            if(array_key_exists($param->name, $routeArgs)){
                $params[] = $routeArgs[$param->name];
            }
        }
        return $params;
    }
    //
    protected static function __executeCallback($callback, $params = array()){
        return call_user_func_array($callback, self::__compareArgs($callback, $params));
    }
    
    protected static function __render($callback, $params = array()){

	$controllerPath = '\\' . BUSINESS_FOLDER . "\\" . $callback[0] . '\\' . Core::get('controller');
        
        $objController = new $controllerPath();
        $out = call_user_func_array(array($objController, Core::get('action')), $params);
        
        View::setName(!empty(View::getName())?View::getName():Core::get('action'));
	        
        View::setViewsPath(!empty(View::$viewsPath)?View::$viewsPath:BUSINESS_DIR . Core::get('module') . DS . Core::get('controller') . DS);
        
        Theme::includeLayoutViewOnTheme(View::getLayout(), View::getViewsPath() , View::getName(), View::getVars());
    }
    
    protected static function __renderOrExecute($callback = array(), $namedParams = array(), $matches = array()) {
       
//        if(empty($callback)){
//            list($callback, $params) = self::__defineModuleControllerAction($matches[1]);
//        }else 
        if(is_array($callback)){
            list($callback, $params) = self::__defineModuleControllerAction($callback);
        }else{
            unset($matches[0]);
            return self::__executeCallback($callback, array_combine($namedParams, $matches));          
        }
        
        return self::__render($callback, $params);
    }

    protected static function __send404($errorTrigger = null){
        echo '404 '. ' - ' . $errorTrigger;
    }
    
    public static function map($prefix = null, $callbackmap = null) {
        if($prefix){
            self::$mapPrefix = rtrim($prefix, '/');
            $callbackmap();
            self::$mapPrefix = '/';
        }else{
            //TODO
            return false;
        }
    }

    public static function add($method, $route, $callback = null){
        
        self::$routes[] = new Route($method, self::$mapPrefix . $route, $callback);        
        return end(self::$routes);
    }
    
    public static function run(){
        
        self::$routes[] = self::$routes[] = new Route('GET|PoST', self::$mapPrefix . '/*', null);        
        $routes = self::$routes;
        
        self::__parseUri();
        //    PR($routes);
        foreach ($routes as $route) {
            
            $pattern = '/^' . str_replace('/','\\/', $route->getRoute()) . '$/i';
            //PR($pattern);
            //PR(self::$requestUri);
            if(preg_match($pattern, self::$requestUri, $matches) && self::__matchRequestMethod($route->getMethod())){
            //pr(explode('/',ltrim(self::$requestUri, '/')));
            //pr($route);
            $callback = self::combineCallbackMatches($route->getCallback(), $matches);
            //pr($matches);
            //pr($callback);

             // exit;
            //unset($matches[0]);

            $route->insertHeaders();

            return self::__renderOrExecute($callback, $route->getNamedArgs(), $matches);
                
            }
        }
        // Route don't match
        self::__send404("URL não encontrada");        
    }
}
