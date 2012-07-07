<?php
include_once('exceptions/ClassNotFoundException.php');
include_once('exceptions/NotFoundException.php');

/**
 * Process request and bind it to the right Controller.
 *
 * @package TakeALookInside
 * @author Sam Verschueren  <sam@irail.be>
 */
class Router {
    
    private $routes = array();
    
    /**
     * Adding routing directions to the Router.
     *
     * @param   route       $urls           The regex-based url to class mapping
     * @param   controller  $controller     Controller that handles the route
     */
    public function addRoute($route, $controller) {
        $this->routes[$route] = $controller;
    }
    
    /**
     * Handles the HTTP Request and binds the url to the right controller.
     */
    public function processRequest() {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $cleanURI = trim($_SERVER['REQUEST_URI'], '/');
        
        $found = false;
        
        foreach($this->routes as $route => $controller) {
            $regex = str_replace('/', '\/', $route);
            
            if(preg_match('/^' . $regex . '$/i', $cleanURI, $parameters)) {
                $found = true;
                
                if(class_exists($controller)) {
                    $object = new $controller;
                    if(method_exists($object, $method)) {
                        $object->$method($parameters);   
                    }
                    else {
                        throw new BadMethodCallException('Can not find the method ' . $method);
                    }
                }
                else {
                    throw new ClassNotFoundException('Class ' . $controller . ' does not exists.');
                }
            }
        }
        
        if(!$found) {
            throw new NotFoundException('URL not found.');
        }
    }
}
?>