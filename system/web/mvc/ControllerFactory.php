<?php
/**
 * Creates a controller based on a name.
 * 
 * @package system.web.mvc
 * @since 2012-07-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class ControllerFactory {
    
    private $defaultController;
    
    /**
     * @param   defaultController   The controller that should be created of none is specified in the create method.
     */
    public function __construct($defaultController) {
        $this->defaultController = $defaultController;    
    }
    
    /**
     * Creates the controller.
     * 
     * @param   controller      The name of the controller.
     * @return  controller      The controller object.
     */
    public function createController($controller='') {
        if(trim($controller) == '') {
            $controller = $this->defaultController;
        }
        else {
            $controller .= 'Controller';
        }
        
        return new $controller;
    }
}
?>