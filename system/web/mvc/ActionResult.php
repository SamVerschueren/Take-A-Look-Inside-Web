<?php
/**
 * Encapsulates the result of an action method and is used to perform a framework-level operation on behalf of the action method.
 * 
 * @package system.web.mvc
 * @since 2012-06-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
abstract class ActionResult {
    /**
     * Enables processing of the result of an action method by a custom type that inherits from the ActionResult class.
     */
    abstract function executeResult();
}
?>