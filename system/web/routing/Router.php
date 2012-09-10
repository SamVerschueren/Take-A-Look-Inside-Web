<?php
require_once('system/web/mvc/ControllerFactory.php');
require_once('system/web/mvc/ControllerActionInvoker.php');
require_once('config/Config.php');

/**
 * Processes request binds it to the right controller.
 *
 * @package system.web.routing
 * @since 2012-07-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class Router {
    
    private $routes = array();
    private $controllerFactory;
    
    public function __construct() {
        $this->controllerFactory = new ControllerFactory('HomeController');   
    }
    
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
     */
    public function processRequest() {
        $cleanURI = str_replace(Config::$SUBDIR, '', $_SERVER['REQUEST_URI']);
        $cleanURI = trim($cleanURI, '/');
        
        $found = false;
        
        foreach($this->routes as $route => $controller) {
            $regex = str_replace('/', '\/', $route);
            
            if(preg_match('/^' . $regex . '$/i', $cleanURI, $parameters)) {
                $found = true;

                // Replacing the {tokens} in the controllername
                foreach(array_keys($parameters) as $key => $value) {
                    $controller = preg_replace('/\{' . $value . '\}/', $parameters[$value], ucfirst($controller));
                }
                
                $controller = $this->controllerFactory->createController($parameters['controller']);
                $action = $parameters['action'];
                
                if(trim($parameters['id'])!='') {
                    $_GET['id']=$parameters['id'];
                }
                
                $actionInvoker = new ControllerActionInvoker();
                return $actionInvoker->invokeAction($controller, $action);
            }
        }
        
        if(!$found) {
            throw new NotFoundException('URL not found.');
        }
    }
}
?>