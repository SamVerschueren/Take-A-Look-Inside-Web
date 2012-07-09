<?php
require_once('IController.php');
require_once('exceptions/runtime/UnsupportedOperationException.php');
require_once('persistence/PersistenceController.php');

/**
 * Controller that handles the GET request (cRud = READ).
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class RController implements IController {
    
    private $persistenceController;
    
    public function __construct() {
        $this->persistenceController = PersistenceController::getInstance();
    }
    
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
        $resource = $parameters['resource'];
        
        $get = $_GET;
        $get['parameters'] = $parameters['parameters'];
        
        $this->persistenceController->$resource($get);
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