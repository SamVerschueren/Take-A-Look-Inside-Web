<?php
require_once('IController.php');
require_once('exceptions/runtime/UnsupportedOperationException.php');

class RController implements IController {
    
    public function post($parameters) {
        throw new UnsupportedOperationException("Creation is not supported.");
    }
    
    public function get($parameters) {
        echo 'Resource: ' . $parameters['resource'] . '<br />';
        echo 'Parameters: ' . $parameters['parameters'] . '<br />';
        echo 'Format: ' . $parameters['format'];
    }
    
    public function put($parameters) {
        throw new UnsupportedOperationException("Update is not supported.");
    }
    
    public function delete($parameters) {
        throw new UnsupportedOperationException("Delete is not supported.");
    }
}
?>