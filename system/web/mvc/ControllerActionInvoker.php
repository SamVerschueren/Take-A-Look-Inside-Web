<?php
require_once('IActionInvoker.php');

/**
 * Represents a class that is responsible for invoking the action methods of a controller.
 * 
 * @package system.web.mvc
 * @since 2012-07-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class ControllerActionInvoker implements IActionInvoker {

    private $actionName;
    private $controller;

    /**
     * @return  actionName  The method invoked by this actioninvoker.
     */
    public function getInvokedAction() {
        return $this->actionName;
    }

    public function getInvokedController() {
        return $this->controller;
    }

    /**
     * Invokes the specified action by using the specified controller.
     * 
     * @param   controller      The controller on which the action should be invoked.
     * @param   actionName      The action that should be invoked.
     */
    public function invokeAction(Controller $controller, $actionName) {
        return $this->invokeActionMethod($controller, $actionName, $_GET);
    }  
    
    /**
     * Invokes the specified action method by using the specified parameters and the controller.
     * 
     * @param   controller      The controller on which the action should be invoked.
     * @param   actionName      The action that should be invoked.
     * @param   parameters      The parameters used to invoke the action.
     */
    public function invokeActionMethod(Controller $controller, $actionName, array $parameters) {
        $this->controller = $controller;
                        
        $actionName = trim($actionName)==''?'Index':trim($actionName); 
        
        $controller->setActionInvoker($this);
        $this->actionName = $actionName;
        
        if(!method_exists($controller, $actionName)) {
            throw new BadMethodCallException('Can not find the method ' . $actionName . ' in ' . get_class($controller));
        }
                    
        $method = new ReflectionMethod($controller, $actionName);
        $params = array();
        foreach ($method->getParameters() as $param) {
            $params[$param->name] = isset($parameters[$param->name])?$parameters[$param->name]:null;
        }
        
        return $method->invokeArgs($controller, $params);    
    }
}
?>