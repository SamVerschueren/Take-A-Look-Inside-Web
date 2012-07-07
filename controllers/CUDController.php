<?php
require_once('IController.php');
require_once('exceptions/runtime/UnsupportedOperationException.php');

/**
 * Controller that handles the GET request (cRud = READ).
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class CUDController implements IController {
    
    /**
     * Creating new RW-object.
     */
    public function post($parameters) {
        
    }
    
    /**
     * Can't get a RW-object. So no getting here!
     */
    public function get($parameters) {
        throw new UnsupportedOperationException("Getting is unsupported.");
    }
    
    /**
     * Updating RW-object.
     */
    public function put($parameters) {
        
    }
    
    /**
     * Deleteting RW-object.
     */
    public function delete($parameters) {
        
    }
}
?>