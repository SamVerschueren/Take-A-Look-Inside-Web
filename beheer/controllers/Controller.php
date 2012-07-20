<?php
abstract class Controller {
    
    private $invokedAction;
    
    public function setInvokedAction($action) {
        $this->invokedAction = $action;
    }
  
    public function view($viewName=null) {
        $controllerName = str_replace('controller', '', strtolower(get_called_class()));
        
        if($viewName == null) {
            include('views/' . $controllerName . '/' . $this->invokedAction . '.html');            
        }
        else {
            include('views/' . $controllerName . '/' . $viewName . '.html');            
        }
    }
}
?>