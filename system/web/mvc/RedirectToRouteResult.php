<?php
require_once('ActionResult.php');
require_once('config/Config.php');

class RedirectToRouteResult extends ActionResult {
    
    private $routeValues;
    
    public function __construct(RouteValueDictionary $routeValueDictionary) {
        $this->routeValues = $routeValueDictionary;
    }
    
    /**
     * Enables processing of the result of an action method by a custom type that inherits from the ActionResult class.
     */
    public function executeResult() {        
        if(!$this->routeValues->tryGetValue('controller', $controller)) {
            $controller = 'Home';
        }
        else {
            $this->routeValues->remove('controller');
        }
        
        if(!$this->routeValues->tryGetValue('action', $action)) {
            $action = 'Index';
        }
        else {
            $this->routeValues->remove('action');
        }
        
        if(!$this->routeValues->tryGetValue('id', $id)) {
            $id = '';
        }
        else {
            $this->routeValues->remove('id');
        }
        
        $parameter = '';
        if($this->routeValues->count() > 0) {
            $parameter = '?';
            
            foreach($this->routeValues->getKeys() as $key) {
                $parameter .= $key . '=' . $this->routeValues->getItem($key);
                
                $this->routeValues->remove($key);
                
                if($this->routeValues->count()>0) {
                    $parameter .= '&';
                }
            }
        }
        
        $subdir = trim(Config::$SUBDIR)==''?'':'/' . Config::$SUBDIR;
        
        header('Location: ' . $subdir . '/' . $controller . '/' . $action . '/' . $id . $parameter);
    }
}
?>