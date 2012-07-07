<?php
require_once('IController.php');
require_once('exceptions/runtime/UnsupportedOperationException.php');

/**
 * Controller that handles the GET request (cRud = READ).
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class RController implements IController {
    
    /**
     * Can not create a representation.
     */
    public function post($parameters) {
        throw new UnsupportedOperationException("Creation is not supported.");
    }
    
    /**
     * The only thing that you can do on a representation.
     */
    public function get($parameters) {
        echo 'Resource: ' . $parameters['resource'] . '<br />';
        echo 'Parameters: ' . $parameters['parameters'] . '<br />';
        echo 'Format: ' . $parameters['format'] . '<br />';
        print_r($_GET);
    }
    
    /**
     * Can not update a representation.
     */
    public function put($parameters) {
        throw new UnsupportedOperationException("Update is not supported.");
    }
    
    /**
     * Can not delete a representation.
     */
    public function delete($parameters) {
        throw new UnsupportedOperationException("Delete is not supported.");
    }
}
?>