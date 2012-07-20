<?php
require_once('exceptions/ClassNotFoundException.php');
require_once('exceptions/NotFoundException.php');
require_once('controllers/HomeController.php');
require_once('Config.php');

/**
 * Processes request binds it to the right controller.
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
     * Handles the HTTP Request and binds the url to the right controller. Method based upon TDT project.
     * 
     * @return void
     */
    public function processRequest() {
        $cleanURI = str_replace(Config::$SUBDIR, '', $_SERVER['REQUEST_URI']);
        $cleanURI = trim($cleanURI, '/');
        
        
        $found = false;
        
        foreach($this->routes as $route => $controller) {
            $regex = str_replace('/', '\/', $route);
            
            if(preg_match('/^' . $regex . '$/i', $cleanURI, $parameters)) {
                $found = true;
                
                $method = trim($parameters['action'])!=''?$parameters['action']:'index';
                
                $controllerName =  trim($parameters['controller'])!=''?$parameters['controller']:'Home';
                $controller = preg_replace("/{controller}/", $controllerName, ucfirst($controller));
                if(class_exists($controller)) {
                    $object = new $controller;
                    $object->setInvokedAction($method);
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