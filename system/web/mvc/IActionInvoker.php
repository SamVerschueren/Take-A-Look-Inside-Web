<?php
/**
 * Defines the contract for an action invoker, which is used to invoke an action in response to an HTTP request.
 * 
 * @package system.web.mvc
 * @since 2012-06-29
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
interface IActionInvoker {
    
    /**
     * Invokes the specified action by using the specified controller.
     * 
     * @param   controller      The controller on which the action should be invoked.
     * @param   actionName      The action that should be invoked.
     */
    public function invokeAction(Controller $controller, $actionName);
}
?>