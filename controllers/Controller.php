<?php
/**
 * Base class. Standard no implementation.
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
abstract class Controller {
    /**
     * Creating
     */
    public function post($parameters) {
        throw new UnsupportedOperationException("Posting is unsupported.");
    }
    
    /**
     * Getting
     */
    public function get($parameters) {
        throw new UnsupportedOperationException("Getting is unsupported.");
    }
    
    /**
     * Updating
     */
    public function put($parameters) {
        throw new UnsupportedOperationException("Putting is unsupported.");
    }
    
    /**
     * Deleteting
     */
    public function delete($parameters) {
        throw new UnsupportedOperationException("Deleteing is unsupported.");
    }
}
?>